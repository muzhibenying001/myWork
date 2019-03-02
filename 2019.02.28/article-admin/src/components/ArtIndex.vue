<template>
<div>
    <el-card shadow="always" class="el-card bread">
        <el-breadcrumb separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>文章管理</el-breadcrumb-item>
            <el-breadcrumb-item>文章列表</el-breadcrumb-item>
        </el-breadcrumb>
    </el-card>
    <el-table :data="tableData3" style="width: 90%;margin: 0 auto;">
        <el-table-column>
            <template slot="header" slot-scope="scope">
                <el-button icon="el-icon-circle-plus-outline" plain  @click="dialog_art_add = true" id="add">添加文章</el-button>
                <el-input placeholder="搜索文章标题" clearable prefix-icon="el-icon-search" 
                size="mini" style="width:250px;float:right;" v-model="search" @keyup.enter.native="getArtData"></el-input>
            </template>
            <el-table-column prop="id" label="ID" width="50"></el-table-column>
            <el-table-column prop="title" label="标题" width="230"></el-table-column>
            <el-table-column prop="user_name" label="作者" width="100"></el-table-column>
            <el-table-column prop="content" label="文章简介"></el-table-column>
            <el-table-column prop="create_time" label="更新时间" width="200"></el-table-column>
            <el-table-column prop="" label="操作" width="200">
                <template slot-scope="scope">
                    <el-button type="primary" size="mini" plain @click="edit_show(scope.row)"><i class="el-icon-edit"></i> 修改</el-button>
                    <el-button type="danger" size="mini" plain @click="delart(scope.row.id)"><i class="el-icon-delete"></i> 删除</el-button>
                </template>
            </el-table-column>
        </el-table-column>
        
    </el-table>
    <div class="block">
        <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" 
        :current-page="pagenum" :page-sizes="[1, 2, 5, 10]" :page-size="pagesize" 
        layout="total, sizes, prev, pager, next, jumper"
        :total="total">
        </el-pagination>
    </div>

    <el-dialog title="添加文章" :visible.sync="dialog_art_add" width="80%">
        <el-form :model="addform" :rules="rules" ref="addform" status-icon>
            <el-form-item label="文章标题" prop="title">
                <el-input v-model="addform.title" autocomplete="off"></el-input>
            </el-form-item>
            <div class="editCOn">
     <quill-editor style="height:280px;" @change="onEditorChange($event)" v-model="addform.content">

     </quill-editor>
   </div>
            <!-- <el-form-item label="内容" prop="content">
                <el-input type="textarea" v-model="addform.content"></el-input>
            </el-form-item> -->
        </el-form>
        <div slot="footer" class="dialog-footer" >
            <el-button @click="clearForm('addform')">取 消</el-button>
            <el-button type="primary" @click="submitForm('addform')">确 定</el-button>
        </div>
    </el-dialog>

    <el-dialog title="修改文章" :visible.sync="dialog_art_edit">
            <el-form :model="editform" :rules="rules" ref="editform" status-icon>
                <el-form-item label="文章标题" prop="title">
                    <el-input v-model="editform.title" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="内容" prop="content">
                    <el-input type="textarea" v-model="editform.content"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <!-- 点击取消或确定修改dialog_art_add=false关闭窗口 -->
                <el-button @click="dialog_art_edit = false">取 消</el-button>
                <el-button type="primary" @click="edit_put('editform')">确 定</el-button>
            </div>
        </el-dialog>
</div>
</template>

<script>

export default {
    data(){
        var title = (rule, value, callback) => {
            if (value === "") {
                callback(new Error("请输入文章标题"));
            } else {
                callback();
            }
            };

        return{ 
            total: 0,
            pagenum: 1,
            pagesize: 5,
            search: '',
            tableData3: [], /** 文章列表数据 */
            dialogTableVisible: false,
            dialog_art_add: false, /** 文章添加弹窗开关 */
            dialog_art_edit: false, /** 文章修改弹窗开关 */
            addform: {}, /** 文章添加表单 */
            editform: {}, /** 文章修改表单 */
            tempdata: {}, /** 临时存储修改前数据 */
            rules: {
               title: [ { required: true, validator: title, trigger: "blur" } ]
            }
        }
    },
    mounted(){
        this.getArtData();        
    },
    methods: {
        /** 获取文章总数据 */
        getArtData() {
            var data = this.$qs.stringify({
                        pagenum: this.pagenum,
                        pagesize: this.pagesize,
                        query: this.search,
                        type: "art",
                    });

            this.$axios({data})
                .then(back_data => {
                    var { data, meta } = back_data.data;
                    if (meta.status === 200) {
                        var { data, meta } = back_data.data;
                        this.tableData3 = data.article;
                        this.total = data.total;
                }
            });
        },
        /** 控制每页数据改变数量 */
        handleSizeChange(val) {
            this.pagesize = val;
            this.getArtData();
        },
        /** 控制数据页码 */
        handleCurrentChange(val) {
            this.pagenum = val;
            this.getArtData();
        },
        /** 文章添加 */
        submitForm(formName) {
            this.$refs[formName].validate(valid => {
                if (valid) {
                    var data = this.$qs.stringify({
                        title: this.addform.title,
                        content: this.addform.content,
                        id: JSON.parse(localStorage.getItem("userinfo")).id,
                        username: JSON.parse(localStorage.getItem("userinfo")).username,
                        type: "artadd",
                    });
                    this.$axios({data})
                        .then(back_data => {
                            var { data, meta } = back_data.data;
                            if (meta.status === 201) {
                                this.getArtData();
                                this.$message({ message: meta.msg, type: "success" });
                                this.addform = {};
                                this.$refs[formName].resetFields();
                                this.dialog_art_add = false;
                            }else{
                                this.$message.error(meta.msg);
                            }
                        });
                } else {
                    // this.$message({ message: '出错啦，好嗨呦！', type: "error" });
                    return false;
                }
            });
        },
        edit_show(data) {
            this.dialog_art_edit = true; // 弹窗
            this.editform = data;
        },
        edit_put(formName) {

            var title = this.editform.title;
            var content = this.editform.content;
            
            this.$refs[formName].validate(valid => {
                if( valid ){
                    var data = this.$qs.stringify({
                        title,
                        content,
                        id: this.editform.id,
                        type: "artedit",
                    });
                    this.$axios({data})
                        .then(back_data => {
                            var { data, meta } = back_data.data;
                            if (meta.status === 200) {
                                this.getArtData();
                                this.$message({ message: meta.msg, type: "success" });
                                this.editform = {};
                                this.$refs[formName].resetFields();
                                this.dialog_art_edit = false;
                            }else{
                                this.$message.error(meta.msg);
                            }
                        });
                }else {
                    // this.$message({ message: '出错啦，好嗨呦！', type: "error" });
                    return false;
                }
            });
        },
        delart(id) {
            this.$confirm("此操作将永久该文章, 是否继续?", "提示", {
                confirmButtonText: "确定",
                cancelButtonText: "取消",
                type: "warning"
            }).then(
                () => {
                    var data = this.$qs.stringify({
                        id,
                        type: "artdel",
                    });
                    this.$axios({data})
                        .then(back_data => {
                            var { data, meta } = back_data.data;
                            if (meta.status === 200) {
                                this.$message({ message: meta.msg, type: "success" });
                                this.getArtData();
                            } else {
                                this.$message.error(meta.msg);
                            }                            
                    });
                
                })
                .catch(
                    () => {
                        this.$message({
                            type: "info",
                            message: "已取消删除"
                    });
                });
            },
        /** 清空弹窗表单数据 */
        clearForm(formName) {
            this.addform = {};
            this.$refs[formName].resetFields();
            this.dialog_art_add = false;
        }
   
    }
}
</script>

<style>
.bread {
  margin: 10px;
}
.sou {
  float: right;
  width: 200px;
}

#add{
    margin: 0 15px;
}

.block {
  text-align: left;
  margin: 10px 80px;
}
</style>
