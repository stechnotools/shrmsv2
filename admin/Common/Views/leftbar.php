<div class="left-side-menu">
    <div class="slimscroll-menu">
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <?php foreach ($menus as $menu) { ?>
                    <li id="<?php echo $menu['id']; ?>" class="<?php echo $menu['children']?'has_sub':'';?>">
                        <?php if ($menu['href']) { ?>
                            <a class="waves-effect" href="<?php echo $menu['href']; ?>"><i class="mdi <?php echo $menu['icon']; ?> "></i> <span><?php echo $menu['name']; ?></span></a>
                        <?php } else { ?>
                            <a class="parent waves-effect"><i class="mdi <?php echo $menu['icon']; ?> "></i> <span><?php echo $menu['name']; ?></span><span class="menu-arrow"></span></a>
                        <?php } ?>
                        <?php if ($menu['children']) { ?>
                            <ul class="nav-second-level" aria-expanded="false">
                                <?php foreach ($menu['children'] as $children_1) { ?>
                                    <li class="<?php echo $children_1['children']?'has_sub':'';?>">
                                        <?php if ($children_1['href']) { ?>
                                            <a href="<?php echo $children_1['href']; ?>"><?php echo $children_1['name']; ?></a>
                                        <?php } else { ?>
                                            <a class="parent waves-effect"><span><?php echo $children_1['name']; ?></span><span class="pull-right"><i class="mdi mdi-add"></i></span></a>
                                        <?php } ?>
                                        <?php if ($children_1['children']) { ?>
                                            <ul class="nav-third-level" aria-expanded="false">
                                                <?php foreach ($children_1['children'] as $children_2) { ?>
                                                    <li>
                                                        <?php if ($children_2['href']) { ?>
                                                            <a href="<?php echo $children_2['href']; ?>"><?php echo $children_2['name']; ?></a>
                                                        <?php } else { ?>
                                                            <a class="parent waves-effect"><?php echo $children_2['name']; ?></a>
                                                        <?php } ?>
                                                        <?php if ($children_2['children']) { ?>
                                                            <ul class="nav-fourth-level" aria-expanded="false">
                                                                <?php foreach ($children_2['children'] as $children_3) { ?>
                                                                    <li><a href="<?php echo $children_3['href']; ?>"><?php echo $children_3['name']; ?></a></li>
                                                                <?php } ?>
                                                            </ul>
                                                        <?php } ?>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        <?php } ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
