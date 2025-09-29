-- Create tblorders table
CREATE TABLE IF NOT EXISTS tblorders (
    ID VARCHAR(50) PRIMARY KEY,
    Email VARCHAR(255) NOT NULL,
    TotalAmount DECIMAL(10,2) NOT NULL,
    Status ENUM('Pending', 'Processing', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (Email),
    INDEX idx_status (Status),
    INDEX idx_order_date (OrderDate)
);

-- Create tblorderitems table
CREATE TABLE IF NOT EXISTS tblorderitems (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID VARCHAR(50) NOT NULL,
    ProductID VARCHAR(50) NOT NULL,
    Quantity INT NOT NULL,
    Price DECIMAL(10,2) NOT NULL,
    Total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES tblorders(ID) ON DELETE CASCADE,
    INDEX idx_order_id (OrderID),
    INDEX idx_product_id (ProductID)
);

-- Optional: Create a view to show order details with items
CREATE OR REPLACE VIEW vw_order_details AS
SELECT
    o.ID as OrderID,
    o.Email,
    o.TotalAmount,
    o.Status,
    o.OrderDate,
    oi.ProductID,
    oi.Quantity,
    oi.Price,
    oi.Total as ItemTotal
FROM tblorders o
LEFT JOIN tblorderitems oi ON o.ID = oi.OrderID
ORDER BY o.OrderDate DESC, o.ID, oi.ID;
