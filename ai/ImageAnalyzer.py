import mysql.connector
from mysql.connector import Error
import numpy as np
from keras.api.models import load_model
from keras.api.preprocessing import image
import os
from datetime import datetime

# Constants
MODEL_CANCER = r"C:\xampp\htdocs\GP\healthVision\ai\models\EffNet4_Cancer.h5"
MODEL_PNEUMONIA = r"C:\xampp\htdocs\GP\healthVision\ai\models\EffNet4_Pneumonia.h5"
BASE_IMAGE_PATH = r"C:/xampp/htdocs/GP/healthVision"


class MedicalImageAnalyzer:
    def __init__(self):
        self.connection = None
        self.cursor = None

    def connect_to_database(self):
        """Establish connection to MySQL database"""
        try:
            self.connection = mysql.connector.connect(
                host='localhost',
                database='hv',
                user='root',
                password=''
            )
            if self.connection.is_connected():
                self.cursor = self.connection.cursor(dictionary=True)
                return True
        except Error as e:
            print(f"Database connection error: {e}")
            return False

    def check_if_already_analyzed(self, image_id):
        """Check if image already has diagnostic results"""
        query = "SELECT * FROM DiagnosticResults WHERE image_id = %s"
        self.cursor.execute(query, (image_id,))
        return self.cursor.fetchone() is not None

    def load_and_prepare_image(self, img_path, target_size=(224, 224)):
        """Load and preprocess image for model prediction"""
        try:
            full_path = os.path.join(BASE_IMAGE_PATH, img_path)
            if not os.path.exists(full_path):
                raise FileNotFoundError(f"Image not found at: {full_path}")

            img = image.load_img(full_path, target_size=target_size)
            img_array = image.img_to_array(img)
            img_array = np.expand_dims(img_array, axis=0)
            img_array = img_array / 255.0  # Normalize to [0,1]
            return img_array
        except Exception as e:
            raise Exception(f"Image processing failed: {str(e)}")

    def make_prediction(self, model, img_array):
        """Make prediction using the loaded model"""
        try:
            print("prediction is running")
            prediction = model.predict(img_array)
            return prediction[0][0]
        except Exception as e:
            raise Exception(f"Prediction failed: {str(e)}")

    def save_diagnostic_result(self, image_id, diagnosis, confidence):
        """Save analysis results to DiagnosticResults table"""
        query = """
         INSERT INTO DiagnosticResults 
        (image_id, diagnosis, confidence)
        VALUES (%s, %s, %s)
        """
        try:
            self.cursor.execute(query, (
                image_id,
                diagnosis,
                float(confidence)
            ))
            self.connection.commit()
            return True
        except Error as e:
            print(f"Failed to save diagnostic result: {e}")
            return False

    def analyze_medical_image(self, image_id):
        """Main function to analyze a medical image"""
        if not self.connect_to_database():
            return False

        try:
            # Check if image already analyzed
            if self.check_if_already_analyzed(image_id):
                print(f"Image {image_id} already has diagnostic results")
                return True

            # Get image record
            query = "SELECT * FROM MedicalImages WHERE image_id = %s"
            self.cursor.execute(query, (image_id,))
            record = self.cursor.fetchone()

            if not record:
                print(f"No image found with ID {image_id}")
                return False

            print("\nMedical Image Details:")
            print("-" * 60)
            for key, value in record.items():
                print(f"{key:15}: {value}")

            # Process the image
            print("\nProcessing image...")
            try:
                if record['image_type'] == 'Mammogram':
                    target_size = (224, 224)
                    model_path = MODEL_CANCER
                    model_type = "Cancer Detection"
                elif record['image_type'] == 'Chest X-ray':
                    target_size = (128, 128)
                    model_path = MODEL_PNEUMONIA
                    model_type = "Pneumonia Detection"
                else:
                    print("Unsupported image type")
                    return False

                # Load model and make prediction
                print(f"\nLoading {model_type} Model...")
                model = load_model(model_path)
                img_array = self.load_and_prepare_image(record['image_path'], target_size)
                prediction = self.make_prediction(model, img_array)

                # Interpret results
                if record['image_type'] == 'Mammogram':
                    diagnosis = "Breast Cancer detected" if prediction > 0.5 else "Benign"
                else:
                    diagnosis = "Normal" if prediction > 0.5 else "Pneumonia detected"

                confidence = prediction if prediction > 0.5 else 1 - prediction
                confidence_percent = round(confidence * 100, 2)

                print(f"\nPrediction Result: {diagnosis} (Confidence: {confidence_percent}%)")

                # Save results to database
                if self.save_diagnostic_result(image_id, diagnosis, confidence_percent):
                    print("Diagnostic results saved successfully")
                    return True
                return False

            except Exception as e:
                print(f"Analysis failed: {str(e)}")
                return False

        finally:
            if self.connection and self.connection.is_connected():
                self.cursor.close()
                self.connection.close()
                print("\nDatabase connection closed")

