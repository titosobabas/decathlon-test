<?php
/* Smarty version 3.1.39, created on 2021-10-19 20:27:39
  from 'C:\xampp\htdocs\decathlon\themes\classic\templates\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_616f708bd38ac7_03004306',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '11907c720c353707a7a62aa86481bcbb91308ee1' => 
    array (
      0 => 'C:\\xampp\\htdocs\\decathlon\\themes\\classic\\templates\\index.tpl',
      1 => 1634680279,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_616f708bd38ac7_03004306 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1551051573616f708bd37161_71934111', 'page_content_container');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_content_top'} */
class Block_1882713424616f708bd37507_63165946 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'hook_home'} */
class Block_1710428819616f708bd37d85_62346549 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php echo $_smarty_tpl->tpl_vars['HOOK_HOME']->value;?>

          <?php
}
}
/* {/block 'hook_home'} */
/* {block 'page_content'} */
class Block_560897454616f708bd37a94_76907021 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1710428819616f708bd37d85_62346549', 'hook_home', $this->tplIndex);
?>

        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_1551051573616f708bd37161_71934111 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content_container' => 
  array (
    0 => 'Block_1551051573616f708bd37161_71934111',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_1882713424616f708bd37507_63165946',
  ),
  'page_content' => 
  array (
    0 => 'Block_560897454616f708bd37a94_76907021',
  ),
  'hook_home' => 
  array (
    0 => 'Block_1710428819616f708bd37d85_62346549',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-home">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1882713424616f708bd37507_63165946', 'page_content_top', $this->tplIndex);
?>


        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_560897454616f708bd37a94_76907021', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
}
