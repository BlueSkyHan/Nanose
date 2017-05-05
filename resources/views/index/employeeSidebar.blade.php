<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{Auth::user()->employee->photo_path}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p style="font-weight: 100">{{Auth::user()->employee->name}}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> 在线</a>
            </div>
        </div>

        <ul class="sidebar-menu">
            <li class="header">主菜单</li>
            <!-- Optionally, you can add icons to the links -->

            <!-- Member -->
            <li>
                <a id="member-index" href="#">
                    <i class="fa fa-child fa-lg"></i> <span>会员</span>
                </a>
            </li>

            <!-- Sales Order -->
            <li>
                <a id="sales-order-index" href="#">
                    <i class="fa fa-file-text fa-lg"></i> <span>订单</span>
                </a>
            </li>

            <!-- Warehouse -->
            <li>
                <a id="warehouse-index" href="#">
                    <i class="fa fa-database"></i> <span>仓库</span>
                </a>
            </li>

            @if(Auth::check())
            @if(Auth::user()->employee->employeeType->name == '门店管理')
            <!-- employee -->
            <li>
                <a id="employee-index" href="#">
                    <i class="fa fa-users"></i> <span>员工</span>
                </a>
            </li>
            @endif
            @endif

            <!-- Product -->
            <li>
                <a id="productType-index" href="#">
                    <i class="fa fa-cube"></i> <span>产品</span>
                </a>
            </li>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>