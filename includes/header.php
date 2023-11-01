<?php
include "../includes/login_access.php";
?>
<style>
     .O {
        font-size: 20px !important;
        color: #FB5607;
        background-color: transparent;
    }hr {
    height: 1px;
    color: black;
}
.header_name{
    color:black;
}

</style>

<header style="" class="navbar_header_fix">
    <nav class="navbar" style="background-color: #e3f2fd;">
        <button class="navbar-toggler-icon navbar-toggler icon" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasDarkNavbar" style="color: black;"><i class="fa-solid fa-bars menu_icon"></i></button>
        <div class="container">
            <div class="company_name">
                <a class="navbar-brand" href="dashboard.php">
                    <img src="../assets/images/logo.png" alt="Bootstrap" width="100%" height="55px">
                </a>
            </div>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">ABOUT</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">CONTACT</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link active" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"
                        aria-current="page">PROFILE</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">ADMIN</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">LOGIN</a></li>
                    </ul>
                </li>
                <div class="icon_pro_bell">

                    <li class="nav-item dropdown">
                        <a class="nav-link active mg_left" data-bs-toggle="dropdown" role="button"
                            aria-expanded="false" aria-current="page" href="#"><i class="fa-regular fa-bell O "></i></a>
                        <ul class="dropdown-menu Profile_dropdown notification_bar">

                            <li class="notification_content">
                                <div class="" id="notificationContent">
                                    <hr class="dropdown-divider">
                                    Notification content goes here.
                                    You have a new notification!
                                    <hr class="dropdown-divider">
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <center><a class="dropdown-item btn_showall" type="button" href="">Show All</a></center>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link active mg_left" data-bs-toggle="dropdown" role="button"
                            aria-expanded="false" aria-current="page" href="#"><i class="far fa-user O"></i></a>
                        <ul class="dropdown-menu Profile_dropdown">
                            <li><a class="dropdown-item" href="#">Profile Details</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../includes/logout.php">Sign out</a></li>
                        </ul>
                    </li>
                </div>
            </ul>
        </div>
    </nav>
    <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar"
        aria-labelledby="offcanvasDarkNavbarLabel">
        <div class="offcanvas-header header_name">
            <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel"><?php echo $employeeName; ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"
                style="color: black;">
            </button>
        </div>
        <div class="offcanvas-body">

        <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <li class="nav-item">
                    <li><hr class="dropdown-divider"></li>
                    <a class="nav-link active" aria-current="page" href="dashboard.php">DASHBOARD</a>
                    <li><hr class="dropdown-divider"></li>
                </li>

                
                <li class="nav-item dropdown">
                <li><hr class="dropdown-divider"></li>
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        TIME OFF
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <?php if ($user_position == "General Manager" || $user_position == "Human Resource Manager" || $user_position == "Admin" || ($user_position == "Employee" && $EMP_TEAMLEADER == "Yes")) { ?>
                            <li><a class="dropdown-item" href="timetracking.php">Requested Timeoff / My Report</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="time_off.php">Time Off</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="calander.php">Calendar</a></li>
                        <?php } else { ?>
                            <li><a class="dropdown-item" href="timetracking.php">Requested Timeoff / My Report</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="calander.php">Calendar</a></li>
                        <?php } ?>
                    </ul>
                    <li><hr class="dropdown-divider"></li>
                </li>
                
                
                
                 
                <li class="nav-item dropdown">
                <li><hr class="dropdown-divider"></li>
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        PAYROLL
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <?php if ($user_position == "General Manager" || $user_position == "Human Resource Manager") { ?>
                            <li><a class="dropdown-item" href="hraccess.php">CLOCKIN ACCESS</a></li>
                            <li><hr class="dropdown-divider"></li>
                            
                        <?php } else { ?>
                            <li><p class="dropdown-item">Authorization Blocked by Admin</p></li>
                        <?php } ?>
                    </ul>
                    <li><hr class="dropdown-divider"></li>
                </li>
                

                <?php if ($user_position == "General Manager" || $user_position == "Admin" || $user_position == "Operational Manager") { ?>
                   
                    <li class="nav-item active">
                    <li><hr class="dropdown-divider"></li>
                        <a class="nav-link" href="employee list.php">EMPLOYEE'S LIST</a>
                    <li><hr class="dropdown-divider"></li>
                    </li>
                <?php } else
                {} ?>

                <li class="nav-item dropdown">
                <li><hr class="dropdown-divider"></li>
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        CANDIDATE'S LIST
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <?php if ($user_position == "Human Resource Manager" || $user_position == "Admin") { ?>
                            <li><a class="dropdown-item" href="#">EMPLOYEE</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">TRAINEE</a></li>
                        <?php } else { ?>
                            <li><p class="dropdown-item">Authorization Blocked by Admin</p></li>
                        <?php } ?>
                    </ul>
                    <li><hr class="dropdown-divider"></li>
                </li>
                
                
                

                
                <li class="nav-item dropdown">
                <li><hr class="dropdown-divider"></li>
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        SALARY
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <?php if ($user_position == "General Manager" || $user_position == "Human Resource Manager") { ?>
                            <li><a class="dropdown-item" href="#">EMPLOYEE</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">TRAINEE</a></li>
                        <?php } else { ?>
                            <li><p class="dropdown-item">Authorization Blocked by Admin</p></li>
                        <?php } ?>
                    </ul>
                    <li><hr class="dropdown-divider"></li>
                </li>
                
                
                

                <li class="nav-item dropdown">
                <li><hr class="dropdown-divider"></li>
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        PAYSLIP
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <?php if ($user_position == "General Manager" || $user_position == "Human Resource Manager" || $user_position == "Admin") { ?>
                            <li><a class="dropdown-item" href="#">EMPLOYEE</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">TRAINEE</a></li>
                        <?php } else { ?>
                            <li><p class="dropdown-item">Authorization Blocked by Admin</p></li>
                        <?php } ?>
                    </ul>
                    <li><hr class="dropdown-divider"></li>
                </li>


                <li class="nav-item dropdown">
                <li><hr class="dropdown-divider"></li>
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">WORK ASSIGN
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <?php if (($user_position == "Employee" && $EMP_TEAMLEADER == "Yes") || $user_position == "Project Manager" || $user_position == "Trainner Department" || $user_position == "Operational Manager" || $user_position == "General Manager") { ?>
                            <li><a class="dropdown-item" href="tl_work.php">Assign</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="tl_view.php">View</a></li>
                        <?php } else { ?>
                            <li><p class="dropdown-item">Authorization Blocked by Admin</p></li>
                        <?php } ?>
                    </ul>
                <li><hr class="dropdown-divider"></li>
                </li>

                <li class="nav-item dropdown">
                <li><hr class="dropdown-divider"></li>
                    <a class="nav-link" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">PROJECTS</a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <?php if ($user_position == "Project Manager" || $user_position == "General Manager" || $user_position == "Operational Manager" || $user_position == "Admin") { ?>
                            <li><a class="dropdown-item" href="project.php">PROJECTS</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="project assign.php">VIEW DETAILS</a></li>
                        <?php } else { ?>
                            <li><p class="dropdown-item">Authorization Blocked by Admin</p></li>
                        <?php } ?>
                    </ul>
                <li><hr class="dropdown-divider"></li>
                </li>
                
                
                 <li class="nav-item dropdown">
                <li><hr class="dropdown-divider"></li>
                    <a class="nav-link" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">REPORTS</a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                      <?php if ($user_position == "Project Manager" || $user_position == "General Manager" || $user_position == "Human Resource Manager") { ?>
                            <li><a class="dropdown-item" href="newreport.php">PROJECT REPORT</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="atreport.php">TIME TRACKING REPORT</a></li>
                        <?php } else { ?>
                            <li><p class="dropdown-item">Authorization Blocked by Admin</p></li>
                        <?php } ?>
                    </ul>
                <li><hr class="dropdown-divider"></li>
                </li>

        


                <li class="nav-item dropdown">
               
                    <?php if ($user_position == "Accounts Manager" || $user_position == "General Manager") { ?>
                        <li><hr class="dropdown-divider"></li>
                        <a class="nav-link active" aria-current="page" href="accounts.php">ACCOUNTS</a>
                        <li><hr class="dropdown-divider"></li>
                    <?php } else { ?>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><p class="dropdown-item">Authorization Blocked by Admin</p></li>
                        </ul>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
    </nav>
</header>
