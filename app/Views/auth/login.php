<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ed 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 20px;
        }
        .login-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .login-card .card-header {
            background: #212529; /* Black matte */
            color: white;
            border-bottom: none;
            padding: 1.5rem;
        }
        .login-card .card-body {
            padding: 2rem;
        }
        .btn-primary {
            background: #212529; /* Black matte */
            border: none;
            padding: 0.75rem;
        }
        .btn-primary:hover {
            background: #343a40; /* Slightly lighter black */
        }
        .form-control:focus {
            border-color: #6c757d; /* Grey */
            box-shadow: 0 0 0 0.25rem rgba(108, 117, 125, 0.25);
        }
        .form-check-input:checked {
            background-color: #6c757d; /* Grey */
            border-color: #6c757d;
        }
        .login-icon {
            font-size: 2rem;
            margin-right: 0.5rem;
        }
        a {
            color: #6c757d; /* Grey */
        }
        a:hover {
            color: #343a40; /* Darker grey */
        }
        .home-link {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>
    <div class="home-link">
        <a href="<?= base_url() ?>" class="btn btn-outline-dark">
            <i class="bi bi-house-door me-1"></i>Home
        </a>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="card-header text-center">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-circle login-icon"></i>
                            <h3 class="card-title mb-0">Login</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (session()->has('message')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <?= session('message') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->has('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <?= session('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?= url_to('attemptLogin') ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-2"></i>Email
                                </label>
                                <input type="email" class="form-control form-control-lg <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                                    id="email" name="email" value="<?= old('email') ?>" placeholder="Enter your email" required>
                                <?php if (session('errors.email')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.email') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-2"></i>Password
                                </label>
                                <input type="password" class="form-control form-control-lg <?= session('errors.password') ? 'is-invalid' : '' ?>" 
                                    id="password" name="password" placeholder="Enter your password" required>
                                <?php if (session('errors.password')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.password') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                                </button>
                            </div>
                        </form>
                        
                        <div class="mt-4 text-center">
                            <a href="<?= url_to('magic-link') ?>" class="text-decoration-none">
                                <i class="bi bi-envelope-fill me-1"></i>Forgot password?
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4 text-muted">
                    <small>&copy; <?= date('Y') ?> Student Management System. All rights reserved.</small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 