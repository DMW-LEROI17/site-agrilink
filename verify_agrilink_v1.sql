USE agrilink_v1;
SELECT 'STATISTIQUES - Base agrilink_v1' as Info;
SELECT CONCAT('Nombre de Produits: ', COUNT(*)) FROM products;
SELECT CONCAT('Nombre de Vendeurs: ', COUNT(*)) FROM sellers;
SELECT CONCAT('Nombre de Régions: ', COUNT(*)) FROM regions;
SELECT CONCAT('Nombre de Catégories: ', COUNT(*)) FROM categories;
SELECT CONCAT('Nombre de Commandes: ', COUNT(*)) FROM orders;
SELECT '--- PRODUITS ---' as Info;
SELECT product_id, product_name, unit_price, quantity_available FROM products;