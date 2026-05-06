-- Base de données agrilink_v1 - Schéma différent et optimisé
CREATE DATABASE IF NOT EXISTS agrilink_v1;
USE agrilink_v1;

-- Table des régions
CREATE TABLE regions (
    region_id INT PRIMARY KEY AUTO_INCREMENT,
    region_name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des vendeurs/producteurs
CREATE TABLE sellers (
    seller_id INT PRIMARY KEY AUTO_INCREMENT,
    seller_name VARCHAR(150) NOT NULL,
    region_id INT NOT NULL,
    rating DECIMAL(3,1) DEFAULT 4.5,
    total_products INT DEFAULT 0,
    contact_phone VARCHAR(20),
    email VARCHAR(100),
    verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (region_id) REFERENCES regions(region_id)
);

-- Table des catégories
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    category_icon VARCHAR(10),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits avec structure différente
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(150) NOT NULL,
    seller_id INT NOT NULL,
    category_id INT NOT NULL,
    region_id INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    quantity_available INT NOT NULL,
    quantity_unit VARCHAR(50),
    product_rating DECIMAL(3,1),
    product_description TEXT,
    quality_grade VARCHAR(20),
    certification VARCHAR(50),
    harvest_date DATE,
    availability_status ENUM('available', 'limited', 'sold_out') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES sellers(seller_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id),
    FOREIGN KEY (region_id) REFERENCES regions(region_id)
);

-- Table des commandes
CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    seller_id INT NOT NULL,
    quantity_ordered INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    order_status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    delivery_date DATE,
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    FOREIGN KEY (seller_id) REFERENCES sellers(seller_id)
);

-- Table des avis clients
CREATE TABLE reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    seller_id INT NOT NULL,
    customer_name VARCHAR(100),
    rating INT CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT,
    review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    FOREIGN KEY (seller_id) REFERENCES sellers(seller_id)
);

-- Table des transactions de financement
CREATE TABLE financing (
    financing_id INT PRIMARY KEY AUTO_INCREMENT,
    seller_id INT NOT NULL,
    product_id INT,
    loan_amount DECIMAL(10,2) NOT NULL,
    interest_rate DECIMAL(5,2),
    loan_status ENUM('pending', 'approved', 'active', 'repaid', 'defaulted') DEFAULT 'pending',
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approval_date DATE,
    repayment_date DATE,
    FOREIGN KEY (seller_id) REFERENCES sellers(seller_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- Table de logistique
CREATE TABLE shipments (
    shipment_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    from_region_id INT,
    to_region_id INT,
    carrier_name VARCHAR(100),
    shipment_status ENUM('preparing', 'in_transit', 'delivered', 'delayed') DEFAULT 'preparing',
    estimated_delivery DATE,
    actual_delivery DATE,
    tracking_number VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (from_region_id) REFERENCES regions(region_id),
    FOREIGN KEY (to_region_id) REFERENCES regions(region_id)
);

-- ===== INSERTION DES DONNÉES =====

-- Régions
INSERT INTO regions (region_name) VALUES 
('Centre'), ('Ouest'), ('Littoral'), ('Est'), ('Sud'), ('Nord'), ('Extrême-Nord');

-- Vendeurs (sellers)
INSERT INTO sellers (seller_name, region_id, rating, contact_phone, verified) VALUES 
('Jean N.', 1, 4.5, '+237671111111', TRUE),
('Alima S.', 2, 4.7, '+237672222222', TRUE),
('Paul M.', 3, 4.3, '+237673333333', TRUE),
('Martine K.', 4, 4.6, '+237674444444', TRUE),
('Cacao SARL', 5, 4.9, '+237675555555', TRUE),
('Ferme Mbankolo', 1, 4.4, '+237676666666', FALSE),
('Femme rurale', 2, 4.8, '+237677777777', FALSE),
('Aviculture Douala', 3, 4.6, '+237678888888', TRUE);

-- Catégories
INSERT INTO categories (category_name, category_icon, description) VALUES 
('Fruits et Légumes', '🥬', 'Produits frais: fruits et légumes'),
('Céréales', '🌾', 'Maïs, riz, blé et autres céréales'),
('Viande et Volaille', '🥩', 'Poulets, viandes fraîches et froides'),
('Produits Laitiers', '🥛', 'Lait, fromage, beurre et dérivés'),
('Produits Phyto', '🧪', 'Pesticides et produits phytosanitaires'),
('Oeufs et Produits Avicoles', '🥚', 'Oeufs et produits dérivés');

-- Produits avec nouvelle structure
INSERT INTO products (product_name, seller_id, category_id, region_id, unit_price, quantity_available, quantity_unit, product_rating, quality_grade, certification, availability_status) VALUES 
('Tomates fraîches', 1, 1, 1, 1200.00, 200, '50 kg', 4.5, 'A', 'Bio', 'available'),
('Maïs blanc', 2, 2, 2, 850.00, 500, '200 kg', 4.7, 'A', 'Standard', 'available'),
('Poulets de chair', 3, 3, 3, 3500.00, 45, '30 unités', 4.3, 'B', 'Standard', 'available'),
('Plantains mûrs', 4, 1, 4, 1800.00, 80, '100 régimes', 4.6, 'A', 'Bio', 'available'),
('Cacao bio AOC', 5, 5, 5, 4500.00, 120, '500 kg', 4.9, 'A+', 'AOC', 'available'),
('Manioc frais', 6, 1, 1, 600.00, 300, '100 kg', 4.4, 'B', 'Bio', 'available'),
('Piment rouge', 7, 1, 2, 800.00, 60, '20 kg', 4.8, 'A', 'Bio', 'available'),
('Oeufs frais', 8, 6, 3, 1500.00, 150, '30 oeufs', 4.6, 'B', 'Standard', 'available');

-- Avis clients
INSERT INTO reviews (product_id, seller_id, customer_name, rating, review_text) VALUES 
(1, 1, 'Client 1', 5, 'Très frais et de bonne qualité'),
(2, 2, 'Client 2', 4, 'Bon produit, livraison rapide'),
(3, 3, 'Client 3', 4, 'Poulets bien présentés'),
(5, 5, 'Client 4', 5, 'Meilleur cacao du Cameroun'),
(8, 8, 'Client 5', 5, 'Oeufs excellents, prix correct');

-- Commandes exemple
INSERT INTO orders (product_id, seller_id, quantity_ordered, total_amount, order_status, payment_method) VALUES 
(1, 1, 5, 6000.00, 'delivered', 'MTN Money'),
(2, 2, 2, 1700.00, 'delivered', 'Orange Money'),
(3, 3, 3, 10500.00, 'confirmed', 'Visa'),
(5, 5, 1, 4500.00, 'pending', 'MTN Money'),
(8, 8, 10, 15000.00, 'shipped', 'Orange Money');

-- Financement exemple
INSERT INTO financing (seller_id, product_id, loan_amount, interest_rate, loan_status, approval_date) VALUES 
(1, 1, 50000.00, 8.5, 'approved', CURDATE()),
(2, 2, 100000.00, 7.5, 'active', DATE_SUB(CURDATE(), INTERVAL 30 DAY)),
(5, 5, 200000.00, 6.0, 'active', DATE_SUB(CURDATE(), INTERVAL 60 DAY));

-- Affichage de vérification
SELECT 'Base agrilink_v1 créée avec succès!' as Status;
SELECT COUNT(*) as 'Nombre de Produits' FROM products;
SELECT COUNT(*) as 'Nombre de Vendeurs' FROM sellers;
SELECT COUNT(*) as 'Nombre de Commandes' FROM orders;