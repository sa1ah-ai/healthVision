import sys
import json
import io
from ImageAnalyzer import MedicalImageAnalyzer

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

def main():
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No image ID provided"}))
        sys.exit(1)

    try:
        image_id = int(sys.argv[1])
    except ValueError:
        print(json.dumps({"error": "Invalid image ID"}))
        sys.exit(1)

    analyzer = MedicalImageAnalyzer()
    result = analyzer.analyze_medical_image(image_id)

    if result:
        print(json.dumps({
            "status": "success",
            "image_id": image_id,
            "diagnosis": result["diagnosis"],
            "confidence": result["confidence"]
        }))
    else:
        print(json.dumps({"status": "failed", "image_id": image_id}))


if __name__ == "__main__":
    main()
