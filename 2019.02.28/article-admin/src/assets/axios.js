import Axios from 'axios';

var my_axios = {};

my_axios.install = function(Vue){

    var obj_axios = Axios.create({
        baseURL: "http://192.168.183.64:8088/api/index.php",
        method: "post",
    });

    Vue.prototype.$axios = obj_axios;
}

export default my_axios;