<style>
    .home {
        background-color: rgba(var(--bs-secondary-bg-rgb), var(--bs-bg-opacity)) !important;
        color: rgba(var(--bs-body-color-rgb), var(--bs-text-opacity)) !important;
        font-weight: 500;
        <?php if (IS_MOBILE) { ?>
            margin-bottom: 0.5rem !important;
        <?php } else { ?>
            margin-right: 0.75rem !important;
        <?php } ?>
    }
    .home:hover {
        text-decoration: none !important;
    }
    .content {
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 90vh;
    }
</style>

<div class="content">
    <div class="container text-center">
        <img src="<?= PUBLIC_URL . "/assets/images/logo.png" ?>" width="250" alt="<?= ucwords(str_replace(['-', '_'], ' ', $_ENV["APP_NAME"])) ?> Logo" class="white-image-dark" />
        <h1>Welcome to <?= $name ?>!</h1>
        <br />
        <p>Start building amazing web applications with ease!</p>
        <a href="https://gitlab.com/fronsky-development/nova/-/raw/main/README.md" class="btn btn-primary default-rounded" target="_blank">View Read Me</a>
    </div>
</div>
