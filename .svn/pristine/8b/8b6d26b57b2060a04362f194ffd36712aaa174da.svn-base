<?php if (TaxConnectionChecking::taxValid() && (TaxConnectionChecking::taxSecondaryValid() || TaxConnectionChecking::nonTaxValid())): ?>

	<div style="text-align: right">

		<?php if (!TaxConnectionChecking::isCurrentConnectionPrimary()): ?>
			<?php echo CHtml::link('Primary', array('/site/select', 'view'=>$view, 'option'=>'1')); ?>
		<?php else: ?>
			<span style="font-weight: bold">Primary</span>
		<?php endif; ?>
		|
		<?php if (!TaxConnectionChecking::isCurrentConnectionSecondary()): ?>
			<?php echo CHtml::link('Secondary', array('/site/select', 'view'=>$view, 'option'=>'2')); ?>
		<?php else: ?>
			<span style="font-weight: bold">Secondary</span>
		<?php endif; ?>

	</div>

<?php endif; ?>