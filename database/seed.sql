USE cake_shop;

-- Insert Admin (Password: admin123)
-- Hash generated using password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert Sample Cakes
INSERT INTO cakes (name, flavor, category, weight, price, image, description, availability) VALUES
('Chocolate Fudge Cake', 'Chocolate', 'Birthday', '1kg', 2500.00, 'chocolate-fudge.jpg', 'Rich and dense chocolate fudge cake with premium cocoa.', TRUE),
('Red Velvet Cake', 'Vanilla & Cocoa', 'Anniversary', '1.5kg', 3200.00, 'red-velvet.jpg', 'Classic red velvet cake with cream cheese frosting.', TRUE),
('Vanilla Celebration Cake', 'Vanilla', 'Other', '1kg', 2000.00, 'vanilla-celebration.jpg', 'Soft vanilla sponge with buttercream icing.', TRUE),
('Strawberry Cream Cake', 'Strawberry', 'Birthday', '1kg', 2400.00, 'strawberry-cream.jpg', 'Fresh strawberry cream cake with real fruit chunks.', TRUE),
('Black Forest Cake', 'Chocolate & Cherry', 'Anniversary', '2kg', 4500.00, 'black-forest.jpg', 'Traditional German black forest cake with cherry liqueur.', TRUE),
('Wedding Special Cake', 'Fruit & Nut', 'Wedding', '3kg', 12000.00, 'wedding-special.jpg', 'Three-tier elegant wedding cake with fondant details.', TRUE),
('Cupcake Assortment', 'Mixed', 'Cupcake', '6 pieces', 1500.00, 'cupcakes.jpg', 'A box of 6 assorted premium cupcakes.', TRUE);

-- Insert Sample Order
INSERT INTO orders (customer_name, customer_phone, customer_email, delivery_address, total_amount, payment_status, order_status, custom_message) VALUES
('John Doe', '0771234567', 'john@example.com', '123 Galle Road, Colombo', 2500.00, 'Paid', 'Confirmed', 'Happy Birthday Jane!');

-- Insert Sample Order Item
INSERT INTO order_items (order_id, cake_id, quantity, price) VALUES
(1, 1, 1, 2500.00);
