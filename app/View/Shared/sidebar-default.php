<div class="menu-icon">
    <div class="line" id="line1"></div>
    <div class="line" id="line2"></div>
    <div class="line" id="line3"></div>
</div>
<div class="sidebar">
    <nav class="nav-sidebar">
        <div class="nav-sidebar-menu-txt">Menu</div>
        <div class="menu">
            <ul>
                <a href="<?= url("/home"); ?>" class="item-link">
                    <li class="menu-item">Home</li>
                </a>
                <a href="<?= url('/home/passaportes'); ?>" class="item-link">
                    <li class="menu-item">Passaportes</li>
                </a>
                <a href="<?= url('/home/background'); ?>" class="item-link">
                    <li class="menu-item">Plano de fundo</li>
                </a>
                <a href="<?= url('/home/alterar-senha'); ?>" class="item-link">
                    <li class="menu-item">Alterar Senha</li>
                </a>
                <a href="<?= url('/logout'); ?>" class="item-link">
                    <li class="menu-item">Logout</li>
                </a>
            </ul>
        </div>
    </nav>
</div>