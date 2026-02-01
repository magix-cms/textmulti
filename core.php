<?php
/**
 * Class plugins_test_core
 * Fichier pour les plugins core
 */
class plugins_textmulti_core extends plugins_textmulti_admin
{
    /**
     * @var object
     */
    protected
        $modelPlugins,
        $plugins;

    /**
     * @var int
     */
    public
        $mod_edit;

    /**
     * @var string
     */
    public
        $mod_action,
        $plugin;

    /**
     * plugins_textmulti_core constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->modelPlugins = new backend_model_plugins();
        $this->plugins = new backend_controller_plugins();
        $formClean = new form_inputEscape();

        if (http_request::isGet('plugin')) $this->plugin = $formClean->simpleClean($_GET['plugin']);
        if (http_request::isRequest('mod_action')) $this->mod_action = $formClean->simpleClean($_REQUEST['mod_action']);
        if (http_request::isGet('mod_edit')) $this->mod_edit = $formClean->numeric($_GET['mod_edit']);
    }
    /**
     *
     */
    protected function runAction()
    {
        switch ($this->mod_action) {
            case 'add':
            case 'edit':
            if( isset($this->content) && !empty($this->content) ) {
                $notify = 'update';
                $revisions = new backend_controller_revisions();

                $currentId = isset($this->content['id']) ? $this->content['id'] : null;
                unset($this->content['id']);

                if (!$currentId) {
                    $this->add([
                        'type' => 'textmulti',
                        'data' => [
                            'module' => $this->controller,
                            'id_module' => $this->edit ?: NULL
                        ]
                    ]);

                    $lasttextmulti = $this->getItems('lasttextmulti', null,'one',false);
                    $currentId = $lasttextmulti['id_textmulti'];
                    $notify = 'add_redirect';
                }

                foreach ($this->content as $lang => $textmulti) {
                    if (!is_array($textmulti)) continue;

                    $textmulti['id_lang'] = (int)$lang;
                    $textmulti['published_textmulti'] = (!isset($textmulti['published_textmulti']) ? 0 : 1);

                    if (!isset($textmulti['desc_textmulti'])) {
                        $textmulti['desc_textmulti'] = '';
                    }

                    $textmultiLang = $this->getItems('textmultiContent', [
                        'id' => $currentId,
                        'id_lang' => $lang
                    ], 'one', false);

                    if($textmultiLang) {
                        $textmulti['id'] = $textmultiLang['id_textmulti'];

                        if (!empty($textmulti['desc_textmulti'])) {
                            $revisions->saveRevision($this->controller, $textmulti['id'], $lang, 'desc_textmulti', $textmulti['desc_textmulti']);
                        }

                        $this->upd(['type' => 'textmultiContent', 'data' => $textmulti]);
                    } else {
                        $textmulti['id_textmulti'] = $currentId;
                        $this->add(['type' => 'textmultiContent', 'data' => $textmulti]);
                    }
                }

                $this->message->json_post_response(true, $notify);
            } else {
                    $this->modelLanguage->getLanguage();
                    if(isset($this->mod_edit)) {
                        $collection = $this->getItems('textmultiContent',$this->mod_edit,'all',false);
                        $setEditData = $this->setItemtextmultiData($collection);
                        $this->template->assign('textmulti', $setEditData[$this->mod_edit]);
                    }

                    $this->template->assign('edit',$this->mod_action === 'edit');
                    $this->modelPlugins->display('mod/edit.tpl');
                }
                break;
            case 'delete':
                if(isset($this->id) && !empty($this->id)) {
                    $this->del([
                        'type' => 'textmulti',
                        'data' => ['id' => $this->id]
                    ]);
                }
                break;
            case 'order':
                if (isset($this->content) && is_array($this->content)) {
                    $this->order('home');
                }
                break;
        }
    }

    /**
     *
     */
    protected function adminList()
    {
        $this->modelLanguage->getLanguage();
        $defaultLanguage = $this->collectionLanguage->fetchData(['context'=>'one','type'=>'default']);
        $this->getItems('textmultis',['lang' => $defaultLanguage['id_lang'], 'module' => $this->controller, 'id_module' => $this->edit ?: NULL],'all');
        $assign = [
            'id_textmulti',
            //'url_textmulti' => ['title' => 'name'],
            //'icon_textmulti' => ['type' => 'bin', 'input' => null, 'class' => ''],
            'title_textmulti' => ['title' => 'name'],
            'desc_textmulti' => ['title' => 'name','type' => 'bin', 'input' => null],
        ];
        $this->data->getScheme(['mc_textmulti', 'mc_textmulti_content'], ['id_textmulti', 'title_textmulti','desc_textmulti'], $assign);
        $this->modelPlugins->display('mod/index.tpl');
    }

    /**
     * Execution du plugin dans un ou plusieurs modules core
     */
    public function run() {
        if(isset($this->controller)) {
            switch ($this->controller) {
                case 'about':
                    $extends = $this->controller.(!isset($this->action) ? '/index.tpl' : '/pages/edit.tpl');
                    break;
                case 'category':
                case 'product':
                    $extends = 'catalog/'.$this->controller.'/edit.tpl';
                    break;
                case 'news':
                case 'catalog':
                    $extends = $this->controller.'/edit.tpl';
                    break;
                case 'pages':
                case 'home':
                    $extends = $this->controller.'/edit.tpl';
                    break;
                default:
                    $extends = 'index.tpl';
            }
            $this->template->assign('extends',$extends);
            if(isset($this->mod_action)) {
                $this->runAction();
            }
            else {
                $this->adminList();
            }
        }
    }
}