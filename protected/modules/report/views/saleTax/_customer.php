<div id="customer_div" class="row">
    <?php echo CHtml::dropDownlist('CustomerId', $customerId, CHtml::listData($customers, 'id', 'company'), array(
        'empty'=>'-- Semua --'
    )); ?>
</div>