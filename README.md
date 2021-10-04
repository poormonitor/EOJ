# HUSTOJ -- Optimized for Teaching

基于HUSTOJ的Python教学优化版。

### 特色功能

- 针对教学班的分组系统和权限管理
- 家长查询学生作业完成情况
- 管理员快速查询作业完成情况
- 优化的排名顺序
- 针对Python优化的查重功能
- 代码填空
- 强制提交使用/不使用关键词
- 特定时间关闭全站
- 全站深色模式
- HIghcharts交互式图表
- 扁平化设计
- 设置安全IP过滤IP重用验证
- 可在已有LNMP平台上部署安装

## 安装

**推荐使用Debian** (脚本只适用于Debian)

```shell
wget https://git.oldmonitor.cn/poormonitor/hustoj/raw/branch/master/install/install.sh && chmod a+x install.sh && bash install.sh
```

运行后，数据库密码会打印。您需要复制密码，自行到数据库中以jol为用户名创建jol库，并将密码设置为上述密码。随后，使用 install/db.sql 初始化数据库。注册admin账号，该账号即为管理员账号。