<?php
function smarty_function_textmulti_data($params, $smarty){
	$modelTemplate = $smarty->tpl_vars['modelTemplate']->value instanceof frontend_model_template ? $smarty->tpl_vars['modelTemplate']->value : new frontend_model_template();
	$textmulti = new plugins_textmulti_public($modelTemplate);
	$assign = isset($params['assign']) ? $params['assign'] : 'textmulti';
	$smarty->assign($assign,$textmulti->gettextmultis($params));
}