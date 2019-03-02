import Vue from 'vue'
import Router from 'vue-router'
import { Message } from 'element-ui';
import Login from '@/components/Login'
import Index from '@/components/Index'
import content from '@/components/content'
import ArtIndex from '@/components/ArtIndex'
import UserIndex from '@/components/UserIndex'

Vue.use(Router)

const router = new Router({
  routes: [
    /** 登陆路由 */
    { path:'/login',name:'Login',component:Login },
    {
      /** 首页路由 */
      path: '/',name: 'Index',component: Index,
      children: [
        {path: '/',name: 'content',component:content},
        /** 文章管理 */
        {path: '/1artindex',name: 'artIndex',component:ArtIndex},
        /** 用户管理 */
        {path: '/2userindex',name: 'userIndex',component:UserIndex}
      ]
    },
    
  ]
});

// 配置路由的拦截器
router.beforeEach((to, from, next) => {
  // 如果访问登录的路由地址，放过
  if (to.name === 'Login') {
    next();
  } else {
    // 如果请求的不是登录页面，验证session
    // 1. 获取localStorage中是否有userinfo
    const userinfo = localStorage.getItem('userinfo');
    if (!userinfo) {
      Message({
        type: 'warning',
        message: '请先登录!'
      });
      // 2. 如果没有userinfo，跳转到登录
      next({
        name: 'Login'
      });
    } else {
      // 3. 如果有userinfo，继续往下执行
      next();
    }
  }
});

export default router;
