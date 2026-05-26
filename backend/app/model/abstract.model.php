<?php 
require_once './config.php';
abstract class modelAbstract{
    protected $db;
    public function __construct(){
        $this->_deployDb();
        $this->db = $this->db = new PDO(
            "mysql:host=".MYSQL_HOST .
            ";dbname=".MYSQL_DB.";charset=utf8", 
            MYSQL_USER, MYSQL_PASS);
            
            $this->_deployUser();
            $this->_deployProduct();
            $this->_deployOrders(); 
            $this->_deployReview();
    }
    private function _deployDb() {
            $db = new PDO(
                "mysql:host=" . MYSQL_HOST . ";charset=utf8", 
                MYSQL_USER, MYSQL_PASS
            );
            $sql = "CREATE DATABASE IF NOT EXISTS `".MYSQL_DB."` 
                    DEFAULT CHARACTER SET utf8mb4 
                    COLLATE utf8mb4_general_ci";
            $db->exec($sql);
            
    }
    private function _deployUser() {
        $query = $this->db->query("SHOW TABLES LIKE 'user'");
        $tables = $query->fetchAll();
        if (count($tables) == 0) {
            $sql =<<<SQL
            CREATE TABLE `user` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `user_name` varchar(250) NOT NULL,
              `password` char(60) NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `userName` (`user_name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            SQL;
            $this->db->query($sql);
            $insertSql = "INSERT INTO user (user_name, password) VALUES (?, ?)";
            $this->db->prepare($insertSql)->execute(['webadmin', '$2y$10$XIxf3cEkb65J2zsFlL32meabNayi2sqgXgzyiAwPujiSk.0zoMnta']);
        }
    }
    private function _deployReview() {
        $query = $this->db->query("SHOW TABLES LIKE 'review'");
        $tables = $query->fetchAll();
        if (count($tables) == 0) {
            $sql = <<<SQL
            CREATE TABLE `review` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `id_product` int(11) DEFAULT NULL,
            `client_name` varchar(100) NOT NULL,
            `score` int(11) NOT NULL,
            `coment` text NOT NULL,
            `reply` text DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `id_product` (`id_product`),
            CONSTRAINT `fk_review_product` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            SQL;
            $this->db->query($sql);
    
            $insertSql = "INSERT INTO `review` (`id`, `id_product`, `client_name`, `score`, `coment`, `reply`) VALUES
            (1, 1, 'Juan Perez', 5, 'Excelente producto, me llegó en perfectas condiciones.', 'Gracias por tu reseña, Juan.'),
            (2, 2, 'Maria García', 4, 'El producto es bueno, pero la entrega fue un poco lenta.', 'Lamentamos el retraso, Maria. Estamos mejorando nuestro servicio.'),
            (3, 5, 'Carlos López', 3, 'El producto cumple, pero esperaba más por el precio.', 'Gracias por tu comentario, Carlos. Valoramos tu feedback.'),
            (4, 6, 'Ana Fernandez', 5, '¡Me encantó! Superó mis expectativas.', 'Nos alegra mucho que te haya gustado, Ana. ¡Gracias!'),
            (5, 7, 'Luis Ramírez', 2, 'El producto llegó con algunos defectos. No estoy satisfecho.', 'Lo sentimos, Luis. Por favor, contáctanos para solucionar el problema.');";
            $this->db->prepare($insertSql)->execute();
        }
    }
    private function _deployOrders() {
        $query = $this->db->query("SHOW TABLES LIKE 'orders'");
        $tables = $query->fetchAll();
        if (count($tables) == 0) {
            $sql = <<<SQL
            CREATE TABLE `orders` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `id_product` int(11) DEFAULT NULL,
              `cant_products` int(11) DEFAULT NULL,
              `total` decimal(10,2) NOT NULL,
              `date` date DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `id_product` (`id_product`),
              CONSTRAINT `fk_product` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            SQL;
            $this->db->query($sql);
    
            $insertSql = "INSERT INTO `orders` (`id`, `id_product`, `cant_products`, `total`, `date`) VALUES
                        (1, 1, 2, 6000.00, '2024-09-11'),
                        (2, 2, 21, 3000.00, '2024-09-19'),
                        (5, 7, 3, 3000.00, '2024-09-12'),
                        (6, 1, 1, 3000.00, '2024-09-30');";
            $this->db->prepare($insertSql)->execute();
        }
    }
    private function _deployProduct() {
        $query = $this->db->query("SHOW TABLES LIKE 'product'");
        $tables = $query->fetchAll();
        if (count($tables) == 0) {
            $sql = <<<SQL
            CREATE TABLE `product` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(100) DEFAULT NULL,
              `price` double DEFAULT NULL,
              `description` varchar(150) DEFAULT NULL,
              `image_product` varchar(120) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            SQL;
    
            $this->db->query($sql);
    
            $insertSql = "INSERT INTO `product` (`id`, `name`, `price`, `description`, `image_product`) VALUES
                        (1, 'Hamburguesa doble con chedar', 3000, 'Hamburguesa doble carne, con chedar, huevo, tomate, lechuga.', 'https://www.carniceriademadrid.es/wp-content/uploads/2022/09/smash-burger-que-es.jpg'),
                        (2, 'Pizza mozzarella', 3000, 'Pizza con salsa de tomate y mucha mozzarella', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTI2hdQeNVlyu20ReOpJcNwdgW0ER5hwxnauQ&s'),
                        (5, 'Papas', 500, 'Papas artesanalmente recolectadas, cortadas y fritas', NULL),
                        (6, 'Picada', 6000, 'Salamin, quesos y aceitunas', NULL),
                        (7, 'Limonada', 1000, 'Jugo fresco de limones exprimidos, genjibre y azucar.', 'https://cdn0.celebritax.com/sites/default/files/styles/amp/public/recetas/limonada.jpg'),
                        (8, 'Coca Cola 1,25lt', 1200, 'Botella de coca cola de litro 25.', 'https://naranjomarket.com/wp-content/uploads/2020/06/CocaCola1.25.jpg');";
    
            $this->db->prepare($insertSql)->execute();
        }
    }
    


}