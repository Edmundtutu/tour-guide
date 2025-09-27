<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Uganda Tour Guide' ?> - <?= APP_NAME ?></title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="<?= View::asset('css/style.css') ?>" rel="stylesheet">
    
    <!-- Additional CSS for specific pages -->
    <?php if (isset($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link href="<?= $css ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= View::url('/') ?>">
                <i class="fas fa-mountain-sun me-2"></i>
                <?= APP_NAME ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if (Auth::isLoggedIn()): ?>
                    <?php $user = Auth::getCurrentUser(); ?>
                    
                    <!-- Role-based Navigation -->
                    <?php if ($user['role'] === 'tourist'): ?>
                        <!-- Tourist Navigation -->
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="<?= View::url('/home') ?>">
                                    <i class="fas fa-home me-1"></i>Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= View::url('/hotels') ?>">
                                    <i class="fas fa-hotel me-1"></i>Hotels
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= View::url('/destinations') ?>">
                                    <i class="fas fa-map-marker-alt me-1"></i>Destinations
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= View::url('/itinerary') ?>">
                                    <i class="fas fa-route me-1"></i>My Itinerary
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= View::url('/my-bookings') ?>">
                                    <i class="fas fa-calendar-check me-1"></i>My Bookings
                                </a>
                            </li>
                        </ul>
                        
                    <?php elseif ($user['role'] === 'host'): ?>
                        <!-- Host Navigation -->
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="<?= View::url('/host/dashboard') ?>">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= View::url('/host/hotels') ?>">
                                    <i class="fas fa-hotel me-1"></i>My Hotels
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= View::url('/host/bookings') ?>">
                                    <i class="fas fa-calendar-check me-1"></i>Bookings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= View::url('/host/calendar') ?>">
                                    <i class="fas fa-calendar-alt me-1"></i>Calendar
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= View::url('/host/subscription') ?>">
                                    <i class="fas fa-crown me-1"></i>Subscription
                                </a>
                            </li>
                        </ul>
                        
                    <?php elseif ($user['role'] === 'admin'): ?>
                        <!-- Admin Navigation -->
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="<?= View::url('/admin/dashboard') ?>">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-users me-1"></i>Users
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?= View::url('/admin/users') ?>">
                                        <i class="fas fa-users me-2"></i>All Users
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?= View::url('/admin/hosts') ?>">
                                        <i class="fas fa-user-tie me-2"></i>Hosts
                                    </a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= View::url('/admin/hotels') ?>">
                                    <i class="fas fa-hotel me-1"></i>Hotels
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= View::url('/admin/bookings') ?>">
                                    <i class="fas fa-calendar-check me-1"></i>Bookings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= View::url('/admin/subscriptions') ?>">
                                    <i class="fas fa-crown me-1"></i>Subscriptions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= View::url('/admin/reports') ?>">
                                    <i class="fas fa-chart-bar me-1"></i>Reports
                                </a>
                            </li>
                        </ul>
                    <?php endif; ?>
                    
                    <!-- User Profile Dropdown -->
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="avatar-sm me-2">
                                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=32&h=32&fit=crop&crop=face" 
                                         alt="Avatar" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                                </div>
                                <div class="d-none d-md-block">
                                    <div class="fw-semibold"><?= htmlspecialchars($user['name']) ?></div>
                                    <small class="text-light opacity-75"><?= ucfirst($user['role']) ?></small>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="dropdown-header">
                                    <div class="fw-semibold"><?= htmlspecialchars($user['name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                
                                <?php if ($user['role'] === 'tourist'): ?>
                                    <li><a class="dropdown-item" href="<?= View::url('/itinerary') ?>">
                                        <i class="fas fa-route me-2"></i>My Itinerary
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?= View::url('/my-bookings') ?>">
                                        <i class="fas fa-calendar-check me-2"></i>My Bookings
                                    </a></li>
                                <?php elseif ($user['role'] === 'host'): ?>
                                    <li><a class="dropdown-item" href="<?= View::url('/host/profile') ?>">
                                        <i class="fas fa-user me-2"></i>My Profile
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?= View::url('/host/dashboard') ?>">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?= View::url('/host/subscription') ?>">
                                        <i class="fas fa-crown me-2"></i>Subscription
                                    </a></li>
                                <?php elseif ($user['role'] === 'admin'): ?>
                                    <li><a class="dropdown-item" href="<?= View::url('/admin/dashboard') ?>">
                                        <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?= View::url('/admin/reports') ?>">
                                        <i class="fas fa-chart-bar me-2"></i>Reports
                                    </a></li>
                                <?php endif; ?>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= View::url('/logout') ?>">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a></li>
                            </ul>
                        </li>
                    </ul>
                    
                <?php else: ?>
                    <!-- Guest Navigation -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= View::url('/') ?>">
                                <i class="fas fa-home me-1"></i>Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= View::url('/hotels') ?>">
                                <i class="fas fa-hotel me-1"></i>Hotels
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= View::url('/destinations') ?>">
                                <i class="fas fa-map-marker-alt me-1"></i>Destinations
                            </a>
                        </li>
                    </ul>
                    
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= View::url('/login') ?>">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= View::url('/register') ?>">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Flash Messages -->
        <?php if ($success = View::flash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($error = View::flash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($errors = View::errors()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Page Content -->
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-mountain-sun me-2"></i>
                        <?= APP_NAME ?>
                    </h5>
                    <p class="text-muted">
                        Discover the beauty of Uganda with our comprehensive tour guide platform. 
                        Find the perfect accommodation and explore amazing destinations.
                    </p>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= View::url('/') ?>" class="text-muted text-decoration-none">Home</a></li>
                        <li><a href="<?= View::url('/hotels') ?>" class="text-muted text-decoration-none">Hotels</a></li>
                        <li><a href="<?= View::url('/destinations') ?>" class="text-muted text-decoration-none">Destinations</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="fw-bold mb-3">For Hosts</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= View::url('/register') ?>" class="text-muted text-decoration-none">Become a Host</a></li>
                        <li><a href="<?= View::url('/host/subscription') ?>" class="text-muted text-decoration-none">Subscription</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="fw-bold mb-3">Contact</h6>
                    <p class="text-muted mb-1">
                        <i class="fas fa-envelope me-2"></i>
                        info@ugandatourguide.com
                    </p>
                    <p class="text-muted mb-1">
                        <i class="fas fa-phone me-2"></i>
                        +256 700 000 000
                    </p>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="social-links">
                        <a href="#" class="text-muted me-3"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-muted me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-muted me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-muted"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="<?= View::asset('js/main.js') ?>"></script>
    
    <!-- Additional JavaScript for specific pages -->
    <?php if (isset($additionalJS)): ?>
        <?php foreach ($additionalJS as $js): ?>
            <script src="<?= $js ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
