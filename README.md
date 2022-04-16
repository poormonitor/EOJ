# HOJ -- Optimized for Teaching

基于HUSTOJ的Python教学优化版。

## 特色功能

- 针对教学班的分组系统和权限管理
- 家长查询学生作业完成情况
- 管理员快速查询作业完成情况
- 优化的排名顺序
- 针对Python优化的查重功能
- 单行/多行代码填空
- 强制提交使用/不使用关键词
- 特定时间关闭全站
- 全站深色模式
- Apache ECharts交互式图表
- 后台使用TinyMCE编辑器
- 扁平化设计
- 设置安全IP过滤IP重用验证
- 可在已有LNMP平台上部署安装
- 支持单选、多选和填空的测验功能
- 自动复制额外文件供程序使用

## 安装

**推荐使用Debian** (脚本只适用于Debian)

1. 运行脚本

```shell
wget https://git.oldmonitor.cn/poormonitor/hoj/raw/branch/master/install/install.sh && chmod a+x install.sh && bash install.sh
```

​	脚本运行结束后，会打印数据库密码。请您复制保存。

2. 创建数据库

   请至本机数据库（MySQL或其兼容者）中以jol为用户名创建jol库，并将密码设置为第一步复制的密码。随后，使用 install/db.sql 初始化数据库。

3. *安装Python

   为加速判题，默认开启了PYTHON_FREE，并在编译中将Python指向/home/judge/py3/bin/python3。您可以选用如下方法安装Python。

   1. 编译安装（以3.10.0为例）

      ```shell
      wget https://www.python.org/ftp/python/3.10.0/Python-3.10.0.tar.xz
      tar -xf Python-3.10.0.tar.xz
      cd Python-3.10.0
      ./configure --prefix=/home/judge/py3 --enable-optimizations
      make
      make install
      ```

   2. Virtualenv安装

      ```shell
      pip3 install virtualenv
      cd /home/judge
      virtualenv --no-site-packages py3
      ```

4. 使用

   注册admin账号自动成为管理员。Enjoy it!

## License

原项目：[zhblue/hustoj](https://github.com/zhblue/hustoj)

基于[GPL v2](https://www.gnu.org/licenses/gpl-3.0.txt) 修改和分发。

This program is free software: you can redistribute it and/or modify it
under the terms of the GNU General Public License Version 2 as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
