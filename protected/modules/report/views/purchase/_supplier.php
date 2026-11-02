    Supplier
    <?php echo CHtml::dropDownlist('SupplierId', $supplierId, CHtml::listData($suppliers, 'id', 'company'), array('empty'=>'-- Supplier --')); ?>