<?php

namespace App\Repositories\Interfaces;

interface CategoryRepositoryInterface
{
    public function getAllCategories();
    // public function getOrderById($orderId);
    // public function deleteOrder($orderId);
    public function createCategory(array $orderDetails);
    // public function updateOrder($orderId, array $newDetails);
}
