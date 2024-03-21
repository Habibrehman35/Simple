CREATE TABLE files2 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    unique_filename VARCHAR(255) NOT NULL,
    upload_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    delete_timestamp TIMESTAMP,
    INDEX delete_timestamp_index (delete_timestamp)
);
