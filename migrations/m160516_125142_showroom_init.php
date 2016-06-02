<?php

use inblank\showroom\migrations\Migration;
use yii\db\Schema;

class m160516_125142_showroom_init extends Migration
{
    const TAB_CATEGORIES = 'categories';
    const TAB_TREE = 'categories_tree';
    const TAB_TYPES = 'types';
    const TAB_PRODUCTS = 'products';
    const TAB_LINKS = 'categories_products';
    const TAB_VENDORS = 'vendors';
    const TAB_PRICES = 'prices';

    public function up()
    {
        // Products types
        $this->createTable($this->tn(self::TAB_TYPES), [
            'id' => Schema::TYPE_PK,
            'slug' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'name' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
        ], $this->tableOptions);
        $this->createIndex('unique_slug', $this->tn(self::TAB_TYPES), 'slug', true);

        // Products
        $this->createTable($this->tn(self::TAB_PRODUCTS), [
            'id' => Schema::TYPE_PK,
            'seller_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'type_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'slug' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'name' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'created_at' => Schema::TYPE_DATETIME . ' DEFAULT NULL',
            'deleted_at' => Schema::TYPE_DATETIME . ' DEFAULT NULL',
        ], $this->tableOptions);
        $this->createIndex('unique_slug', $this->tn(self::TAB_PRODUCTS), 'slug', true);
        $this->createIndex('seller', $this->tn(self::TAB_PRODUCTS), 'seller_id');
        $this->createIndex('type', $this->tn(self::TAB_PRODUCTS), 'type_id');
        $this->addForeignKey(
            $this->fk(self::TAB_PRODUCTS, self::TAB_TYPES),
            $this->tn(self::TAB_PRODUCTS), 'type_id',
            $this->tn(self::TAB_TYPES), 'id',
            'RESTRICT', 'RESTRICT'
        );

        // Product categories table
        $this->createTable($this->tn(self::TAB_CATEGORIES), [
            'id' => Schema::TYPE_PK,
            'slug' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'name' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'created_at' => Schema::TYPE_DATETIME . ' DEFAULT NULL',
            'deleted_at' => Schema::TYPE_DATETIME . ' DEFAULT NULL',
        ], $this->tableOptions);
        $this->createIndex('unique_slug', $this->tn(self::TAB_CATEGORIES), 'slug', true);

        // Categories tree
        $this->createTable($this->tn(self::TAB_TREE), [
            'id' => Schema::TYPE_PK,
            'lft' => Schema::TYPE_INTEGER . ' NOT NULL',
            'rgt' => Schema::TYPE_INTEGER . ' NOT NULL',
            'depth' => Schema::TYPE_INTEGER . ' NOT NULL',
            'tree' => Schema::TYPE_INTEGER . ' NOT NULL',
            'category_id' => Schema::TYPE_INTEGER . " NOT NULL",
        ], $this->tableOptions);
        $this->createIndex('category', $this->tn(self::TAB_TREE), 'category_id');
        $this->addForeignKey(
            $this->fk(self::TAB_CATEGORIES, self::TAB_TREE),
            $this->tn(self::TAB_TREE), 'category_id',
            $this->tn(self::TAB_CATEGORIES), 'id',
            'RESTRICT', 'RESTRICT'
        );

        // Category and products links
        $this->createTable($this->tn(self::TAB_LINKS), [
            'category_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'product_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'sort' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ], $this->tableOptions);
        $this->addPrimaryKey('', $this->tn(self::TAB_LINKS), ['category_id', 'product_id']);
        $this->createIndex('product', $this->tn(self::TAB_LINKS), ['product_id']);
        $this->createIndex('sort', $this->tn(self::TAB_LINKS), ['category_id', 'sort']);
        $this->addForeignKey(
            $this->fk(self::TAB_LINKS, self::TAB_CATEGORIES),
            $this->tn(self::TAB_LINKS), 'category_id',
            $this->tn(self::TAB_CATEGORIES), 'id',
            'CASCADE', 'RESTRICT'
        );
        $this->addForeignKey(
            $this->fk(self::TAB_LINKS, self::TAB_PRODUCTS),
            $this->tn(self::TAB_LINKS), 'product_id',
            $this->tn(self::TAB_PRODUCTS), 'id',
            'CASCADE', 'RESTRICT'
        );

        // Vendors
        $this->createTable($this->tn(self::TAB_VENDORS), [
            'id' => Schema::TYPE_PK,
            'slug' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
            'name' => Schema::TYPE_STRING . "(255) NOT NULL DEFAULT ''",
        ], $this->tableOptions);
        $this->createIndex('unique_slug', $this->tn(self::TAB_VENDORS), 'slug', true);

        // Current products price
        $this->createTable($this->tn(self::TAB_PRICES), [
            'seller_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'product_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'vendor_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'price' => Schema::TYPE_FLOAT . ' NOT NULL DEFAULT 0',
            'available_count' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ], $this->tableOptions . ' PARTITION BY KEY([[seller_id]]) PARTITIONS 10');
        $this->addPrimaryKey('', $this->tn(self::TAB_PRICES), ['seller_id', 'product_id', 'vendor_id']);
        $this->createIndex('seller', $this->tn(self::TAB_PRICES), 'seller_id');
        $this->createIndex('vendor', $this->tn(self::TAB_PRICES), 'vendor_id');
        $this->createIndex('products', $this->tn(self::TAB_PRICES), 'product_id');
    }

    public function down()
    {
        $tables = [
            self::TAB_PRICES,
            self::TAB_VENDORS,
            self::TAB_LINKS,
            self::TAB_TYPES,
            self::TAB_PRODUCTS,
            self::TAB_TREE,
            self::TAB_CATEGORIES,
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
