USE agrilink_v1;
SELECT 'PRODUITS' as Type, COUNT(*) as Count FROM products;
SELECT 'VENDEURS' as Type, COUNT(*) as Count FROM sellers;
SELECT 'COMMANDES' as Type, COUNT(*) as Count FROM orders;
SELECT 'REGIONS' as Type, COUNT(*) as Count FROM regions;
SELECT '=== BASE OK ===' as Status;
