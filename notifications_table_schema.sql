CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_uuid VARCHAR(4) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) DEFAULT 0,
    FOREIGN KEY (farmer_uuid) REFERENCES farmers(uuid)
);
