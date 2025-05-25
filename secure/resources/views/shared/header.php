<nav class="navbar navbar-expand-lg bg-body fixed-top header-border">
    <div class="container">
        <a class="navbar-brand" href="<?= PUBLIC_URL . "/" ?>">
            <img src="<?= PUBLIC_URL . "/assets/images/icons/icon.png" ?>" class="default-rounded white-image-dark" width="45" alt="<?= ucwords(str_replace(['-', '_'], ' ', $_ENV["APP_NAME"])) ?> Icon">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list text-body" id="menu-open-icon"></i>
            <i class="bi bi-x-lg text-body d-none" id="menu-close-icon"></i>
        </button>

        <?php if (IS_MOBILE) { ?>
        <div class="collapse navbar-collapse mt-3" id="navbarNav">
            <?php } else { ?>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php } ?>
                <ul class="navbar-nav ms-auto">
                    <?php if (IS_MOBILE) { ?>
                        <li class="nav-item">
                            <a class="nav-link p-2 default-rounded home" aria-current="page" href="<?= PUBLIC_URL . "/" ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2 default-rounded" aria-current="page" target="_blank" href="https://fronsky.com/project/nova">Project Info</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link p-2 default-rounded home" aria-current="page" href="<?= PUBLIC_URL . "/" ?>" style="margin-right: 0.8rem;">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2 default-rounded" aria-current="page" target="_blank" href="https://fronsky.com/project/nova" style="margin-right: 0.8rem;">Project Info</a>
                        </li>
                    <?php } ?>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if (IS_MOBILE) { ?>
                        <hr style="margin: 0.25rem 0;">
                        <li class="nav-item mb-2 mt-2">
                            <a class="p-2 default-rounded" aria-current="page" target="_blank" href="https://gitlab.com/fronsky-development/nova" style="margin-right: 0.5rem;color:var(--bs-nav-link-color);"><i class="bi bi-gitlab"></i></a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link p-2 default-rounded" aria-current="page" target="_blank" href="https://gitlab.com/fronsky-development/nova" style="margin-right: 0.8rem;"><i class="bi bi-gitlab"></i></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</nav>

<br /><br />
