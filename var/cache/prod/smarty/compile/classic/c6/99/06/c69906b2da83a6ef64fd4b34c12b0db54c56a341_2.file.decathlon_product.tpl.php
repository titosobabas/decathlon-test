<?php
/* Smarty version 3.1.39, created on 2021-10-19 20:27:39
  from 'C:\xampp\htdocs\decathlon\modules\decathlon\views\templates\hook\decathlon_product.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_616f708bcd58a6_13043290',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c69906b2da83a6ef64fd4b34c12b0db54c56a341' => 
    array (
      0 => 'C:\\xampp\\htdocs\\decathlon\\modules\\decathlon\\views\\templates\\hook\\decathlon_product.tpl',
      1 => 1634692173,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_616f708bcd58a6_13043290 (Smarty_Internal_Template $_smarty_tpl) {
?><p>Producto obtenido desde el módulo de Decathlon por Fernando Torres:</p>
<h2><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['decathlon_product']->value['models'][0]['webLabel'][0]['value'], ENT_QUOTES, 'UTF-8');?>
</h2>
<h3>ID del producto: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['decathlon_product']->value['models'][0]['code'], ENT_QUOTES, 'UTF-8');?>
</h3>
<p><strong>Diseñado para: </strong> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['decathlon_product']->value['designedFor'][0]['value'], ENT_QUOTES, 'UTF-8');?>
</p>
<?php }
}
