<style>
    .account {
        font-size: 8pt;
    }

    .summary {
        font-weight: bold;
    }

    .number {
        text-align: right;
    }
</style>

<table style="margin: 0 auto; width: 70%; font-size: larger">
    <!--sale-->
    <tr>
        <td></td>
        <td><?php echo CHtml::value($accounts['sale'], 'name'); ?></td>
        <td></td>
    </tr>	
    <?php foreach ($accounts['sale']->accounts as $account): ?>
        <?php if ($account->branch_id == $branchId): ?>
            <tr>
                <td class="account"><?php echo CHtml::encode(CHtml::value($account, 'code')); ?></td>
                <td class="account"><?php echo CHtml::encode(CHtml::value($account, 'name')); ?></td>
                <td class="number"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $account->getBalanceTotal($endDate, $branchId))); ?></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>		
    <tr>
        <td></td>
        <td></td>
        <td style="border-top: 1px solid;"></td>
        <td class="number"><?php echo Yii::app()->numberFormatter->format('#,##0.00', $row['sale_amount']); ?></td>
    </tr>

    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <tr>
        <td></td>
        <td>Stock Awal</td>
        <td class="number"><?php echo Yii::app()->numberFormatter->format('#,##0.00', $row['beginning_stock_amount']); ?></td>
        <td></td>
    </tr>

    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <!--purchase-->
    <tr>
        <td></td>
        <td><?php echo CHtml::value($accounts['purchase'], 'name'); ?></td>
        <td></td>
    </tr>	
    <?php foreach ($accounts['purchase']->accounts as $account): ?>
        <?php if ($account->branch_id == $branchId): ?>
            <tr>
                <td class="account"><?php echo CHtml::encode(CHtml::value($account, 'code')); ?></td>
                <td class="account"><?php echo CHtml::encode(CHtml::value($account, 'name')); ?></td>
                <td class="number"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $account->getBalanceTotal($endDate, $branchId))); ?></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>		
    <tr>
        <td></td> 
        <td style="border-top: 1px solid;">Barang Siap Jual</td>
        <td style="border-top: 1px solid;" class="number"><?php echo Yii::app()->numberFormatter->format('#,##0.00', $row['purchase_amount']); ?></td>
    </tr>

    <tr>
        <td></td>
        <td>Stock Akhir</td>
        <td class="number"><?php echo Yii::app()->numberFormatter->format('#,##0.00', $row['ending_stock_amount']); ?></td>
    </tr>

    <tr>
        <td>HPP</td>
        <td></td>
        <td style="border-top: 1px solid;"></td>
        <td class="number"><?php echo Yii::app()->numberFormatter->format('#,##0.00', $row['cogs']); ?></td>
    </tr>

    <tr>
        <td></td>
        <td style="border-top: 1px solid;" class="summary">Laba Kotor</td>
        <td style="border-top: 1px solid;"></td>
        <td style="border-top: 1px solid;" class="summary number"><?php echo Yii::app()->numberFormatter->format('#,##0.00', $row['gross']); ?></td>
    </tr>

    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <!--expense-->
    <tr>
        <td></td>
        <td><?php echo CHtml::value($accounts['expense'], 'name'); ?></td>
        <td></td>
    </tr>	
    <?php foreach ($accounts['expense']->accountCategories as $accountCategory): ?>
        <?php foreach ($accountCategory->accounts as $account): ?>
            <?php if ($account->branch_id == $branchId): ?>
                <tr>
                    <td class="account"><?php echo CHtml::encode(CHtml::value($account, 'code')); ?></td>
                    <td class="account"><?php echo CHtml::encode(CHtml::value($account, 'name')); ?></td>
                    <td class="number"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $account->getBalanceTotal($endDate, $branchId))); ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>		
    <tr>
        <td>Total Biaya</td>
        <td></td>
        <td style="border-top: 1px solid;"></td>
        <td class="number"><?php echo Yii::app()->numberFormatter->format('#,##0.00', $row['expense_amount']); ?></td>
    </tr>

    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <!--other income-->
    <tr>
        <td></td>
        <td><?php echo CHtml::value($accounts['otherIncome'], 'name'); ?></td>
        <td></td>
    </tr>	
    <?php foreach ($accounts['otherIncome']->accounts as $account): ?>
        <?php if ($account->branch_id == $branchId): ?>
            <tr>
                <td class="account"><?php echo CHtml::encode(CHtml::value($account, 'code')); ?></td>
                <td class="account"><?php echo CHtml::encode(CHtml::value($account, 'name')); ?></td>
                <td class="number"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $account->getBalanceTotal($endDate, $branchId))); ?></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>		
    <tr>
        <td>Total Pendapatan lain2</td>
        <td></td>
        <td style="border-top: 1px solid;"></td>
        <td class="number"><?php echo Yii::app()->numberFormatter->format('#,##0.00', $row['other_income_amount']); ?></td>
    </tr>

    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <!--other expense-->
    <tr>
        <td></td>
        <td><?php echo CHtml::value($accounts['otherExpense'], 'name'); ?></td>
        <td></td>
    </tr>	
    <?php foreach ($accounts['otherExpense']->accounts as $account): ?>
        <?php if ($account->branch_id == $branchId): ?>
            <tr>
                <td class="account"><?php echo CHtml::encode(CHtml::value($account, 'code')); ?></td>
                <td class="account"><?php echo CHtml::encode(CHtml::value($account, 'name')); ?></td>
                <td class="number"><?php echo CHtml::encode(Yii::app()->numberFormatter->format('#,##0.00', $account->getBalanceTotal($endDate, $branchId))); ?></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>		
    <tr>
        <td>Total Biaya lain2</td>
        <td></td>
        <td style="border-top: 1px solid;"></td>
        <td class="number"><?php echo Yii::app()->numberFormatter->format('#,##0.00', $row['other_expense_amount']); ?></td>
    </tr>

    <tr>
        <td></td>
        <td style="border-top: 1px solid;" class="summary">Laba / Rugi</td>
        <td style="border-top: 1px solid;"></td>
        <td style="border-top: 1px solid;" class="summary number"><?php echo Yii::app()->numberFormatter->format('#,##0.00', $row['profit_loss']); ?></td>
    </tr>
</table>
