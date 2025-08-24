<?php
require_once ('db.php');
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2021 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.
 #
 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 #
 # DISCLAIMER
 #
 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
/**
 * @category plugins
 * @package textmulti
 * @copyright  MAGIX CMS Copyright (c) 2011 - 2013 Gerits Aurelien, http://www.magix-dev.be, http://www.magix-cms.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 2.0
 * @create 26-08-2011
 * @Update 12-04-2021
 * @author Gérits Aurélien <contact@magix-dev.be>
 * @author Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 * @name plugins_textmulti_admin
 */
class plugins_textmulti_admin extends plugins_textmulti_db
{
    /**
     * @var object
     */
    protected
        $controller,
        $message,
        $template,
        $plugins,
        $modelLanguage,
        $collectionLanguage,
        $data,
        $header,
        $upload,
        $imagesComponent,
        $routingUrl,
        $finder,
        $makeFiles;

	/**
	 * @var string
	 */
	public
        $getlang,
        $action,
        $tab,
        $img;

	/**
	 * @var int
	 */
	public
        $edit,
        $id;

	/**
	 * @var array
	 */
	public
        $textmulti = [];

    /**
     * plugins_textmulti_admin constructor.
     */
	public function __construct()
    {
		$this->template = new backend_model_template();
		$this->plugins = new backend_controller_plugins();
		$this->message = new component_core_message($this->template);
		$this->modelLanguage = new backend_model_language($this->template);
		$this->collectionLanguage = new component_collections_language();
		$this->data = new backend_model_data($this);
		$this->header = new http_header();
		$this->upload = new component_files_upload();
		$this->routingUrl = new component_routing_url();
        $this->finder = new file_finder();
		$formClean = new form_inputEscape();

		// --- Get
		if (http_request::isGet('controller')) $this->controller = $formClean->simpleClean($_GET['controller']);
        if($this->controller === 'textmulti') $this->controller = 'home';
		if (http_request::isGet('edit')) $this->edit = $formClean->numeric($_GET['edit']);
		if (http_request::isRequest('action')) $this->action = $formClean->simpleClean($_REQUEST['action']);
		if (http_request::isGet('tabs')) $this->tab = $formClean->simpleClean($_GET['tabs']);

		// --- Post
        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                if(is_array($arr)) {
                    foreach ($arr as $k => $v) {
                        $array[$key][$k] = ($k == 'desc_textmulti') ? $formClean->cleanQuote($v) : $formClean->simpleClean($v);
                    }
                }
            }
            $this->content = $array;
        }
		if (http_request::isPost('id')) $this->id = $formClean->simpleClean($_POST['id']);

		// --- Order
		if (http_request::isPost('textmulti')) $this->textmulti = $formClean->arrayClean($_POST['textmulti']);
	}

	/**
	 * Method to override the name of the plugin in the admin menu
	 * @return string
	 */
	public function getExtensionName(): string
	{
		return $this->template->getConfigVars('textmulti_plugin');
	}

	// --- Database actions

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @return false|null|array
	 */
	protected function getItems(string $type, $id = null, $context = null, $assign = true)
    {
		return $this->data->getItems($type, $id, $context, $assign);
	}

    /**
     * Insert data
     * @param array $config
     */
    protected function add(array $config)
    {
        switch ($config['type']) {
            case 'textmulti':
            case 'textmultiContent':
                parent::insert(
                    ['type' => $config['type']],
                    $config['data']
                );
                break;
        }
    }

    /**
     * Update data
     * @param array $config
     */
    protected function upd(array $config)
    {
        switch ($config['type']) {
            case 'textmultiContent':
            case 'order':
                parent::update(
                    ['type' => $config['type']],
                    $config['data']
                );
                break;
        }
    }

    /**
     * Delete a record
     * @param array $config
     */
    protected function del(array $config)
    {
        switch ($config['type']) {
            case 'textmulti':
                parent::delete(
                    ['type' => $config['type']],
                    $config['data']
                );
                $this->message->json_post_response(true,'delete',array('id' => $this->id));
                break;
        }
    }

    // ---

    /**
     * @param $type
     */
    protected function order($type){
        switch ($type) {
            case 'home':
                for ($i = 0; $i < count($this->textmulti); $i++) {
                    $this->upd(['type' => 'order', 'data' => ['id_textmulti' => $this->textmulti[$i], 'order_textmulti' => $i]]);
                }
                break;
        }
    }

	/**
	 * @param array $data
	 * @return array
	 */
	protected function setItemtextmultiData(array $data): array
	{
		$arr = [];
		if(!empty($data)) {

            foreach ($data as $textmulti) {
                if (!array_key_exists($textmulti['id_textmulti'], $arr)) {
                    $arr[$textmulti['id_textmulti']] = array();
                    $arr[$textmulti['id_textmulti']]['id_textmulti'] = $textmulti['id_textmulti'];
                }
                $arr[$textmulti['id_textmulti']]['content'][$textmulti['id_lang']] = [
                    'id_lang' => $textmulti['id_lang'],
                    'title_textmulti' => $textmulti['title_textmulti'],
                    'desc_textmulti' => $textmulti['desc_textmulti'],
                    'published_textmulti' => $textmulti['published_textmulti']
                ];
            }
        }
		return $arr;
	}
}