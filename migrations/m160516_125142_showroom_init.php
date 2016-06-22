<?php

use inblank\showroom\migrations\Migration;
use yii\db\Schema;

class m160516_125142_showroom_init extends Migration
{
    const TAB_SELLERS = 'sellers';
    const TAB_SELLERS_PROFILES = 'sellers_profiles';
    const TAB_SELLERS_ADDRESSES = 'sellers_addresses';
    const TAB_CATEGORIES = 'categories';
    const TAB_TREE = 'categories_tree';
    const TAB_TYPES = 'types';
    const TAB_PRODUCTS = 'products';
    const TAB_LINKS = 'categories_products';
    const TAB_VENDORS = 'vendors';
    const TAB_PRICES = 'prices';

    public function up()
    {
        // Sellers
        $tab = $this->tn(self::TAB_SELLERS);
        $this->createTable($tab, [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'logo' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'name' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'slug' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'created_at' => Schema::TYPE_DATETIME . ' DEFAULT NULL',
        ], $this->tableOptions);
        $this->createIndex('unique_slug', $tab, 'slug', true);

        // Seller profiles
        $tab = $this->tn(self::TAB_SELLERS_PROFILES);
        $this->createTable($tab, [
            'seller_id' => Schema::TYPE_PK,
            'web' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'description' => Schema::TYPE_TEXT . " NOT NULL",
        ], $this->tableOptions);
        $this->addForeignKey(
            $this->fk(self::TAB_SELLERS_PROFILES, self::TAB_SELLERS),
            $tab, 'seller_id',
            $this->tn(self::TAB_SELLERS), 'id',
            'CASCADE', 'RESTRICT'
        );

        // Seller addresses
        $tab = $this->tn(self::TAB_SELLERS_ADDRESSES);
        $this->createTable($tab, [
            'id' => Schema::TYPE_PK,
            'seller_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'sort' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'title' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'lat' => Schema::TYPE_FLOAT . ' NOT NULL DEFAULT 0',
            'lng' => Schema::TYPE_FLOAT . ' NOT NULL DEFAULT 0',
            'address' => Schema::TYPE_TEXT . " NOT NULL",
            'emails' => Schema::TYPE_TEXT . " NOT NULL",
            'phones' => Schema::TYPE_TEXT . " NOT NULL",
            'persons' => Schema::TYPE_TEXT . " NOT NULL",
            'schedule' => Schema::TYPE_TEXT . " NOT NULL",
            'description' => Schema::TYPE_TEXT . ' NOT NULL'
        ], $this->tableOptions);
        $this->addForeignKey(
            $this->fk(self::TAB_SELLERS_ADDRESSES, self::TAB_SELLERS),
            $tab, 'seller_id',
            $this->tn(self::TAB_SELLERS), 'id',
            'CASCADE', 'RESTRICT'
        );

        // Products types
        $tab = $this->tn(self::TAB_TYPES);
        $this->createTable($tab, [
            'id' => Schema::TYPE_PK,
            'slug' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'name' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
        ], $this->tableOptions);
        $this->createIndex('unique_slug', $tab, 'slug', true);

        // Products
        $tab = $this->tn(self::TAB_PRODUCTS);
        $this->createTable($tab, [
            'id' => Schema::TYPE_PK,
            'kind' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'seller_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'type_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'slug' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'name' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'created_at' => Schema::TYPE_DATETIME . ' DEFAULT NULL',
        ], $this->tableOptions);
        $this->createIndex('unique_slug', $tab, 'slug', true);
        $this->createIndex('seller', $tab, 'seller_id');
        $this->createIndex('type', $tab, 'type_id');
        $this->addForeignKey(
            $this->fk(self::TAB_PRODUCTS, self::TAB_SELLERS),
            $tab, 'seller_id',
            $this->tn(self::TAB_SELLERS), 'id',
            'CASCADE', 'RESTRICT'
        );
        $this->addForeignKey(
            $this->fk(self::TAB_PRODUCTS, self::TAB_TYPES),
            $tab, 'type_id',
            $this->tn(self::TAB_TYPES), 'id',
            'RESTRICT', 'RESTRICT'
        );

        // Product categories table
        $tab = $this->tn(self::TAB_CATEGORIES);
        $this->createTable($tab, [
            'id' => Schema::TYPE_PK,
            'slug' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'name' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'created_at' => Schema::TYPE_DATETIME . ' DEFAULT NULL',
        ], $this->tableOptions);
        $this->createIndex('unique_slug', $tab, 'slug', true);

        // Categories tree
        $tab = $this->tn(self::TAB_TREE);
        $this->createTable($tab, [
            'id' => Schema::TYPE_PK,
            'lft' => Schema::TYPE_INTEGER . ' NOT NULL',
            'rgt' => Schema::TYPE_INTEGER . ' NOT NULL',
            'depth' => Schema::TYPE_INTEGER . ' NOT NULL',
            'tree' => Schema::TYPE_INTEGER . ' NOT NULL',
            'category_id' => Schema::TYPE_INTEGER . " NOT NULL",
        ], $this->tableOptions);
        $this->createIndex('category', $tab, 'category_id');
        $this->addForeignKey(
            $this->fk(self::TAB_CATEGORIES, self::TAB_TREE),
            $tab, 'category_id',
            $this->tn(self::TAB_CATEGORIES), 'id',
            'RESTRICT', 'RESTRICT'
        );

        // Category and products links
        $tab = $this->tn(self::TAB_LINKS);
        $this->createTable($tab, [
            'category_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'product_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'sort' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ], $this->tableOptions);
        $this->addPrimaryKey($this->pk(self::TAB_LINKS), $tab, ['category_id', 'product_id']);
        $this->createIndex('product', $tab, ['product_id']);
        $this->createIndex('sort', $tab, ['category_id', 'sort']);
        $this->addForeignKey(
            $this->fk(self::TAB_LINKS, self::TAB_CATEGORIES),
            $tab, 'category_id',
            $this->tn(self::TAB_CATEGORIES), 'id',
            'CASCADE', 'RESTRICT'
        );
        $this->addForeignKey(
            $this->fk(self::TAB_LINKS, self::TAB_PRODUCTS),
            $tab, 'product_id',
            $this->tn(self::TAB_PRODUCTS), 'id',
            'CASCADE', 'RESTRICT'
        );

        // Vendors
        $tab = $this->tn(self::TAB_VENDORS);
        $this->createTable($tab, [
            'id' => Schema::TYPE_PK,
            'slug' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'name' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
        ], $this->tableOptions);
        $this->createIndex('unique_slug', $tab, 'slug', true);

        // Current products price
        $tab = $this->tn(self::TAB_PRICES);
        $this->createTable($tab, [
            'seller_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'product_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'vendor_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'price' => Schema::TYPE_FLOAT . ' NOT NULL DEFAULT 0',
            'available_count' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ], $this->tableOptions . ' PARTITION BY KEY([[seller_id]]) PARTITIONS 10');
        $this->addPrimaryKey($this->pk(self::TAB_PRICES), $tab, ['seller_id', 'product_id', 'vendor_id']);
        $this->createIndex('seller', $tab, 'seller_id');
        $this->createIndex('vendor', $tab, 'vendor_id');
        $this->createIndex('products', $tab, 'product_id');
    }

    public function down()
    {
        $tables = [
            self::TAB_PRICES,
            self::TAB_VENDORS,
            self::TAB_LINKS,
            self::TAB_PRODUCTS,
            self::TAB_TYPES,
            self::TAB_TREE,
            self::TAB_CATEGORIES,
            self::TAB_SELLERS_ADDRESSES,
            self::TAB_SELLERS_PROFILES,
            self::TAB_SELLERS,
        ];
        foreach ($tables as $table) {
            $this->dropTable($this->tn($table));
        }
        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
