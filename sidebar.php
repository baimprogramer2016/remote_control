<?php
include("function_menu.php");
?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <div
          class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top"
        >
          <a class="sidebar-brand brand-logo" href="main.php"
            ><img
              style="width: 180px; height: 40px"
              src="assets/images/akebono.png"
              alt="logo"
          /></a>
          <a class="sidebar-brand brand-logo-mini" href="main.php"
            ><h1>A</h1></a
          >
        </div>
        <ul class="nav">
          <li class="nav-item profile">
            <div class="profile-desc">
              <div class="profile-pic">
                <div class="count-indicator">
                  <img
                    width="35px"
                    height="35px"
                    class="rounded-circle"
                    src='<?php echo "https://api-gateway.akebono-astra.co.id/bebas/api/photo/".$_SESSION['npk'].'.png';?>';
                    alt=""
                  />
                  <span class="count bg-success"></span>
                </div>
                <div class="profile-name">
                  <h5 class="mb-0 font-weight-normal"><?php echo $_SESSION['name'];?></h5>
                  <span><?php echo $_SESSION['npk']?></span>
                </div>
              </div>

              <?php
              if($_SESSION['role'] == 'ADMIN')
              {
              ?>
              <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
              <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
                <a href="main.php?page=setting-access" class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-key-variant text-success"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1 text-small">Access Settings</p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="main.php?page=setting-role" class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-settings text-primary"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1 text-small">Role Settings</p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
            
                <a href="main.php?page=setting-account" class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-account-plus text-danger"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject ellipsis mb-1 text-small">Account settings</p>
                  </div>
                </a>

              </div>
              <?php
              }
              ?>
            </div>
          </li>
          <li class="nav-item nav-category">
            <span class="nav-link">Energy Monitoring</span>
          </li>
          <?php if(checkMenu($_SESSION['access'],'dashboard') || $_SESSION['role'] == 'ADMIN'){?>
          <li class="nav-item menu-items" id="dashboard">
            <a class="nav-link" href="main.php?page=dashboard">
              <span class="menu-icon">
                <i class="mdi mdi-view-dashboard"></i>
              </span>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <?php } ?>
          <?php if(checkMenu($_SESSION['access'],'monitoring-energy') || $_SESSION['role'] == 'ADMIN'){?>
          <li class="nav-item menu-items" id="monitoring-energy">
            <a class="nav-link" href="main.php?page=monitoring-energy">
              <span class="menu-icon">
                <i class="mdi mdi-monitor"></i>
              </span>
              <span class="menu-title">Monitoring</span>
            </a>
          </li>
          <?php } ?>
          <li class="nav-item menu-items">
          <?php if(checkHeadMenu($_SESSION['head_access'],'report') || $_SESSION['role'] == 'ADMIN'){?>
            <a
              class="nav-link"
              data-toggle="collapse"
              href="#ui-report"
              aria-expanded="false"
              aria-controls="ui-report"
            >
              <span class="menu-icon">
                <i class="mdi mdi-file-multiple"></i>
              </span>
              <span class="menu-title">Report</span>
              <i class="menu-arrow"></i>
            </a>
            <?php } ?>
            <div class="collapse report" id="ui-report">
              <ul class="nav flex-column sub-menu ">
                <?php if(checkMenu($_SESSION['access'],'report-power-house') || $_SESSION['role'] == 'ADMIN'){?>
                <li class="nav-item" id="report-power-house">
                  <a class="nav-link" href="main.php?page=report-power-house">Power House</a>
                </li>
                <?php } ?>
                <?php if(checkMenu($_SESSION['access'],'report-power-house-distribution') || $_SESSION['role'] == 'ADMIN'){?>
                <li class="nav-item" id="report-power-house-distribution">
                  <a class="nav-link" href="main.php?page=report-power-house-distribution">Distribution</a>
                </li>
                <?php } ?>
                <?php if(checkMenu($_SESSION['access'],'report-water') || $_SESSION['role'] == 'ADMIN'){?>
                <li class="nav-item" id="report-water">
                  <a class="nav-link" href="main.php?page=report-water">Water</a>
                </li>
                <?php } ?>
                <?php if(checkMenu($_SESSION['access'],'report-water-distribution') || $_SESSION['role'] == 'ADMIN'){?>
                <li class="nav-item" id="report-water-distribution">
                  <a class="nav-link" href="main.php?page=report-water-distribution">Distribution Water</a>
                </li>
                <?php } ?>
                <?php if(checkMenu($_SESSION['access'],'report-compressor') || $_SESSION['role'] == 'ADMIN'){?>
                <li class="nav-item" id="report-compressor">
                  <a class="nav-link" href="main.php?page=report-compressor">Compressor</a>
                </li>
                <?php } ?>
              </ul>
            </div>
          </li>

          <li class="nav-item nav-category">
            <span class="nav-link">Lighting & AC Remote Control</span>
          </li>
          <li class="nav-item menu-items">
          <?php if(checkHeadMenu($_SESSION['head_access'],'schedule') || $_SESSION['role'] == 'ADMIN'){?>
            <a
              class="nav-link"
              data-toggle="collapse"
              href="#ui-lighting"
              aria-expanded="false"
              aria-controls="ui-lighting"
            >
              <span class="menu-icon">
                <i class="mdi mdi-timer"></i>
              </span>
              <span class="menu-title">Schedule Setting </span>
              <i class="menu-arrow"></i>
            </a>
            <?php } ?>
            <div class="collapse schedule" id="ui-lighting">
              <ul class="nav flex-column sub-menu ">
                <?php if(checkMenu($_SESSION['access'],'setting-day') || $_SESSION['role'] == 'ADMIN'){?>
                <li class="nav-item" id="setting-day">
                  <a class="nav-link" href="main.php?page=setting-day"
                    >Day Settings</a
                  >
                </li>
                <?php } ?>
                <?php if(checkMenu($_SESSION['access'],'schedule-ac') || $_SESSION['role'] == 'ADMIN'){?>
                <li class="nav-item" id="schedule-ac">
                  <a class="nav-link" href="main.php?page=schedule-ac"
                    >Air Conditioner</a
                  >
                </li>
                <?php } ?>
                <?php if(checkMenu($_SESSION['access'],'schedule-lighting') || $_SESSION['role'] == 'ADMIN'){?>
                <li class="nav-item" id="schedule-lighting">
                  <a class="nav-link" href="main.php?page=schedule-lighting">Lighting</a>
                </li>
                <?php } ?>
                <?php if(checkMenu($_SESSION['access'],'schedule-compressor') || $_SESSION['role'] == 'ADMIN'){?>
                <li class="nav-item" id="schedule-compressor">
                  <a class="nav-link" href="main.php?page=schedule-compressor">Compressor</a>
                </li>
                <?php } ?>
              </ul>
            </div>
          </li>
          <li class="nav-item menu-items">
          <?php if(checkHeadMenu($_SESSION['head_access'],'remote') || $_SESSION['role'] == 'ADMIN'){?>
            <a
              class="nav-link"
              data-toggle="collapse"
              href="#ui-remote"
              aria-expanded="false"
              aria-controls="ui-remote"
            >
              <span class="menu-icon">
                <i class="mdi mdi-remote"></i>
              </span>
              <span class="menu-title">Remote Control</span>
              <i class="menu-arrow"></i>
            </a>
            <?php } ?>
            <div class="collapse schedule" id="ui-remote">
              <ul class="nav flex-column sub-menu ">
                <?php if(checkMenu($_SESSION['access'],'group-setting-ac') || $_SESSION['role'] == 'ADMIN'){?>
                <li class="nav-item" id="group-setting-ac">
                  <a class="nav-link" href="main.php?page=group-setting-ac"
                    >Air Conditioner</a
                  >
                </li>
                <?php } ?>
                <?php if(checkMenu($_SESSION['access'],'group-setting-lighting') || $_SESSION['role'] == 'ADMIN'){?>
                <li class="nav-item" id="group-setting-lighting">
                  <a class="nav-link" href="main.php?page=group-setting-lighting">Lighting</a>
                </li>
                <?php } ?>
                <?php if(checkMenu($_SESSION['access'],'group-setting-compressor') || $_SESSION['role'] == 'ADMIN'){?>
                <li class="nav-item" id="group-setting-compressor">
                  <a class="nav-link" href="main.php?page=group-setting-compressor">Compressor</a>
                </li>
                <?php } ?>
              </ul>
            </div>
          </li>
          <!-- <li class="nav-item menu-items " id="group-setting-ac">
            <a class="nav-link" href="main.php?page=group-setting-ac">
              <span class="menu-icon">
                <i class="mdi mdi-air-conditioner"></i>
              </span>
              <span class="menu-title">Air Conditioner</span>
            </a>
          </li> -->

          <!-- <li class="nav-item menu-items" id="group-setting-lighting">
            <a class="nav-link" href="main.php?page=group-setting-lighting">
              <span class="menu-icon">
                <i class="mdi mdi-white-balance-incandescent"></i>
              </span>
              <span class="menu-title">Lighting</span>
            </a>
          </li> -->
          <?php if(checkMenu($_SESSION['access'],'logs') || $_SESSION['role'] == 'ADMIN'){?>
          <li class="nav-item menu-items " id="logs">
            <a class="nav-link" href="main.php?page=logs">
              <span class="menu-icon">
                <i class="mdi mdi-history"></i>
              </span>
              <span class="menu-title">Logs</span>
            </a>
          </li>
          <?php } ?>
          <li class="nav-item menu-items logs">
            <a class="nav-link" href="logout.php">
              <span class="menu-icon">
                <i class="mdi mdi-logout-variant"></i>
              </span>
              <span class="menu-title">Logout</span>
            </a>
          </li>
          
          <!-- <li class="nav-item menu-items">
            <a class="nav-link" href="report.html">
              <span class="menu-icon">
                <i class="mdi mdi-library-books"></i>
              </span>
              <span class="menu-title">Report</span>
            </a>
          </li> -->
        </ul>
      </nav>