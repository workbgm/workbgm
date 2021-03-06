<?php
namespace app\common\controller;
use think\Request;
use think\View;
use think\Session;
use org\Auth;
use think\Db;

/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/4/1
 * Time: 16:50
 * For:
 */
class AddonsBase extends  AdminBase
{

    protected $addonsModule;
    protected  $addonsAction;
    protected $view;

    protected function _initialize()
    {
        parent::_initialize();
        $this->assign('moduleType','addon');
    }
    /**
     * 架构函数
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(Request $request = null)
    {
        // 生成request对象
        $this->request = is_null($request) ? Request::instance() : $request;
        // 处理路由参数
        $module = $this->request->param('m', '');
        $action=$this->request->param('ac', '');
        $this->addonsModule =$module;
        $this->addonsAction=$action;
        $this->request->module('addons');
        $this->request->controller($this->addonsModule);
        $this->request->action($this->addonsAction);
        $this->view= new view();
        parent::__construct($request);
    }

    public function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        $this->view->config('view_path',ROOT_PATH  .'addons'.DS );
        $this->view->config('view_depr',DS.'view'.DS);
        return $this->view->fetch($template, $vars, $replace, $config); // TODO: Change the autogenerated stub
    }

    public  function display($content = '', $vars = [], $replace = [], $config = [])
    {
        return $this->view->display($content, $vars, $replace, $config); // TODO: Change the autogenerated stub
    }

    /**
     * 获取侧边栏菜单
     */
    protected function getMenu()
    {
        $menu     = [];
        $admin_id = Session::get('admin_id');
        $auth     = new Auth();

        $auth_rule_list = Db::name('auth_rule')->where('status', 1)->order(['sort' => 'DESC', 'id' => 'ASC'])->select();

        foreach ($auth_rule_list as $value) {
            if ($auth->check($value['name'], $admin_id) || $admin_id == 1) {
                $menu[] = $value;
            }
        }
        $menu = !empty($menu) ? array2tree($menu) : [];
        $nav_1_id  = $this->request->param('m');
        if(!empty($nav_1_id)){
            Session::set('nav_1_id', $nav_1_id);
        }
        $this->assign('menu2', $this->getMenu2(self::getModules(),Session::get('nav_1_id')));
        $nav_2_id  = $this->request->param('ac');
        if(!empty($nav_2_id)){
            Session::set('nav_2_id', $nav_2_id);
        }
        $this->assign('menu', $menu);
    }

    protected  function  getMenu2($menus,$name){
        foreach ($menus as $menu){
            if($menu['name']==$name){
                if(isset($menu['actions'])){
                    return $menu['actions'];
                }else{
                    return null;
                }

            }
        }
    }

        //模块数据
    public function getModules(){
        $actionsData = [];
        foreach (glob('addons/*') as $v) {
            //存在文件说明是合法的模块
            if (is_file($v . '/maintest.php')) {
                $actionsData[] = include $v . '/maintest.php';
            }
        }
        return $actionsData;
    }

}