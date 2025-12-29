<?php

$id=$_SESSION['id'];
$pic = "SELECt * FROM manufacturer WHERE man_id='$id'";

 $query_pic = mysqli_query($conn,$pic);

 $result_pic = mysqli_fetch_assoc($query_pic);
// print_r($result);

?>

<div class="topbar">
                  <nav class="navbar navbar-expand-lg navbar-light">
                     <div class="full">
                        <button type="button" id="sidebarCollapse" class="sidebar_toggle"><i class="fa fa-bars"></i></button>
                        
                        <div class="right_topbar">
                           <div class="icon_info">
                              
                              <ul class="user_profile_dd">
                                 <li>
                                    <a class="dropdown-toggle" data-toggle="dropdown"><img class="img-responsive rounded-circle" src="../images/<?php echo $result_pic['man_photo']?>" alt="#" height="35" width="35"  onerror="this.src='../images/default.png'"/><span class="name_user"><?php print_r($_SESSION['email']); ?> </span></a>
                                    <div class="dropdown-menu">
                                      
                                       <a class="dropdown-item" href="functions/logout_fun.php"><span>Log Out</span> <i class="fa fa-sign-out"></i></a>
                                    </div>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </nav>
               </div>