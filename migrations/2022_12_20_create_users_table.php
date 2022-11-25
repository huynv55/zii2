<?php
use App\Migrations\AppMigrationAbstract;

return new class extends AppMigrationAbstract
{
    public function up()
    {
        $this->addExecuteSql('CREATE TABLE `users`  (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
            `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
            `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
            `reset_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
            `reset_at` datetime NULL DEFAULT NULL,
            `reset_expires` datetime NULL DEFAULT NULL,
            `activate_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
            `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
            `status_message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
            `active` tinyint(1) NOT NULL DEFAULT 0,
            `force_pass_reset` tinyint(1) NOT NULL DEFAULT 0,
            `created_at` datetime NULL DEFAULT NULL,
            `updated_at` datetime NULL DEFAULT NULL,
            `deleted_at` datetime NULL DEFAULT NULL,
            `meta_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `email`(`email` ASC) USING BTREE,
            UNIQUE INDEX `username`(`username` ASC) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic');
        
    }

    public function down()
    {
        $this->addExecuteSql('DROP TABLE IF EXISTS `users`');
    }

    public function getName(): string
    {
        return pathinfo(__FILE__, PATHINFO_FILENAME);
    }
}
?>