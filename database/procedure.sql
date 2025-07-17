DELIMITER $$
CREATE PROCEDURE InsertPatient (
    IN p_name VARCHAR(255),
    IN p_username VARCHAR(50),
    IN p_email VARCHAR(100),
    IN p_passwd VARCHAR(255),
    IN p_dob DATE,
    IN p_Gender ENUM('Male', 'Female'),
    IN p_contact VARCHAR(15)
)
BEGIN
    DECLARE hashed_passwd VARCHAR(255);
    DECLARE new_user_id INT;
    
    SET hashed_passwd = SHA(p_passwd);
    
    INSERT INTO Users (name, username, email, password, role) 
    VALUES (p_name, p_username, p_email, hashed_passwd, 'patient');
    
    SET new_user_id = LAST_INSERT_ID();
    
    INSERT INTO Patients (patient_id, date_of_birth, gender, contact_number) 
    VALUES (new_user_id, p_dob, p_Gender, p_contact);
END$$
DELIMITER ;

DELIMITER $$

CREATE PROCEDURE InsertDoctor (
    IN p_name VARCHAR(255),
    IN p_username VARCHAR(50),
    IN p_email VARCHAR(100),
    IN p_passwd VARCHAR(255),
    IN p_specialization VARCHAR(100),
    IN p_license_number VARCHAR(50),
    IN p_contact VARCHAR(15)
)
BEGIN
    DECLARE hashed_passwd VARCHAR(255);
    DECLARE new_user_id INT;
    
    SET hashed_passwd = SHA(p_passwd);
    
    INSERT INTO Users (name, username, email, password, role) 
    VALUES (p_name, p_username, p_email, hashed_passwd, 'doctor');
    
    SET new_user_id = LAST_INSERT_ID();
    
    INSERT INTO Doctors (doctor_id, specialization, license_number, contact_number) 
    VALUES (new_user_id, p_specialization, p_license_number, p_contact);
END$$

DELIMITER ;

-- Test InsertPatient Procedure
CALL InsertPatient(
    'patient1', 
    'patient1@example.com', 
    'PatientPass123', 
    '1990-05-15', 
    'Male', 
    '0551234567'
);

-- Verify Patient Insertion
SELECT * FROM Users WHERE username = 'patient1';
SELECT * FROM Patients WHERE patient_id = (SELECT user_id FROM Users WHERE username = 'patient1');

-- Test InsertDoctor Procedure
CALL InsertDoctor(
    'doctor1', 
    'doctor1@example.com', 
    'DoctorPass123', 
    'Radiology', 
    'DOC1211', 
    '0569876543'
);

-- Verify Doctor Insertion
SELECT * FROM Users WHERE username = 'doctor1';
SELECT * FROM Doctors WHERE doctor_id = (SELECT user_id FROM Users WHERE username = 'doctor1');



    

DELIMITER $$

CREATE PROCEDURE UserLogin (
    IN p_username VARCHAR(50),
    IN p_passwd VARCHAR(255)
)
BEGIN
    DECLARE user_role ENUM('patient', 'doctor');

    -- Check if the user exists and fetch their role
    SELECT role INTO user_role 
    FROM Users 
    WHERE username = p_username AND password_hash = SHA(p_passwd);
    
    -- If the user is a doctor, retrieve their full details
    IF user_role = 'doctor' THEN
        SELECT u.*, d.*
        FROM Users u
        JOIN Doctors d ON u.user_id = d.doctor_id
        WHERE u.username = p_username;
        
    -- If the user is a patient, retrieve their full details
    ELSEIF user_role = 'patient' THEN
        SELECT u.*, p.*
        FROM Users u
        JOIN Patients p ON u.user_id = p.patient_id
        WHERE u.username = p_username;
        
    ELSE
        -- Return an empty result if login fails
        SELECT '0' AS message;
    END IF;
END$$

DELIMITER ;