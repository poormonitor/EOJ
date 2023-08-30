# EOJ -- Optimized for Teaching

基于 HUSTOJ 的 Python 教学优化版。

## 特色功能

- 针对教学班的分组系统和权限管理
- 用户班级批量修改
- 家长查询学生作业完成情况
- 管理员快速查询作业完成情况
- 优化的排名顺序
- 针对 Python 优化的查重功能
- 单行/多行代码填空
- 不在前端显示的备选题库
- 强制提交使用/不使用关键词
- 特定时间关闭全站
- 全站自动深色模式
- Monaco Editor 代码编辑
- Apache ECharts 交互式图表
- 后台使用 TinyMCE 编辑器
- UI扁平化，统一字体
- 设置安全IP过滤IP重用验证
- 可在已有 LNMP 平台上部署安装
- 支持单选、多选和填空的测验功能
- 自动复制额外文件供程序使用
- 自定义预处理脚本处理输入数据
- 用户操作历史记录

## 安装

### 直接安装

**推荐使用 Debian** (脚本只适用于 Debian)

1. 运行脚本

```shell
wget https://raw.githubusercontent.com/poormonitor/EOJ/master/install/install.sh && chmod a+x install.sh && bash install.sh
```

​	脚本运行结束后，会打印数据库密码。请您复制保存。

2. 创建数据库

   请至本机数据库（MySQL或其兼容数据库）中以 jol 为用户名创建 jol 库，并将密码设置为第一步复制的密码。随后，使用 install/db.sql 初始化数据库。

3. *安装Python

   为加速判题，默认开启了 PYTHON_FREE。若需使用虚拟环境，您可以修改配置文件 OJ_PY_BIN 指向 /home/judge/py3/bin/python3 （自定），并选用如下方法安装Python。如需使用在线安装模块功能，需设置 Python 安装文件夹所有者为 www。

   1. 编译安装（以3.10.0为例）

      ```shell
      wget https://www.python.org/ftp/python/3.10.0/Python-3.10.0.tar.xz
      tar -xf Python-3.10.0.tar.xz
      cd Python-3.10.0
      ./configure --prefix=/home/judge/py3 --enable-optimizations
      make
      make install
      ```

   2. virtualenv 安装

      ```shell
      pip3 install virtualenv
      cd /home/judge
      virtualenv --no-site-packages py3
      ```

4. 使用

   注册 admin 账号自动成为管理员。Enjoy it!

### Docker安装

   基于 Github Action 每周构建 Docker 镜像。

   ```shell
   docker run -d             \
      --name EOJ             \
      -p 8080:80             \
      -v ~/volume:/home/eoj  \
      poormonitor/EOJ:latest
   ```

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
