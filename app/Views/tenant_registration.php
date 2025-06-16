<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Registration - FMS</title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        html, body { 
            height: 100%; 
            margin: 0; 
            background: linear-gradient(135deg, var(--primary) 0%, hsl(from var(--primary) h s calc(l - 15%)) 100%);
            color: var(--foreground); 
            font-family: var(--font-sans); 
        }
        
        .registration-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem 1rem;
        }
        
        .registration-card { 
            background: var(--card); 
            color: var(--card-foreground); 
            border-radius: calc(var(--radius) * 2); 
            box-shadow: var(--shadow-2xl); 
            border: 1px solid var(--border); 
            padding: 2.5rem; 
            width: 100%; 
            max-width: 800px;
            backdrop-filter: blur(10px);
        }
        
        .registration-title { 
            font-size: 2rem; 
            font-weight: 800; 
            margin-bottom: 0.5rem; 
            text-align: center; 
            color: var(--foreground);
            letter-spacing: -0.025em;
        }
        
        .registration-subtitle {
            text-align: center;
            color: var(--muted-foreground);
            margin-bottom: 2rem;
            font-size: 1rem;
            line-height: 1.5;
        }
        
        .form-section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--foreground);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label { 
            font-family: var(--font-sans); 
            font-size: 0.875rem; 
            font-weight: 600;
            color: var(--foreground);
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .form-control, .form-select { 
            font-family: var(--font-sans); 
            font-size: 1rem;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border);
            border-radius: var(--radius);
            background: var(--background);
            color: var(--foreground);
            transition: all 0.2s ease;
            width: 100%;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px hsl(from var(--primary) h s l / 0.1);
            outline: none;
        }
        
        .btn-enhanced { 
            border-radius: var(--radius) !important; 
            box-shadow: var(--shadow-md); 
            font-family: var(--font-sans); 
            border: none; 
            font-size: 1rem; 
            font-weight: 600;
            padding: 0.875rem 2rem;
            transition: all 0.2s ease;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
        }
        
        .btn-primary-enhanced { 
            background: var(--primary) !important; 
            color: var(--primary-foreground) !important; 
        }
        
        .btn-primary-enhanced:hover {
            background: hsl(from var(--primary) h s calc(l - 10%)) !important;
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-outline-enhanced {
            background: transparent;
            border: 2px solid var(--border);
            color: var(--foreground);
        }
        
        .btn-outline-enhanced:hover {
            background: var(--muted);
            border-color: var(--primary);
        }
        
        .alert { 
            margin-bottom: 1.5rem; 
            font-size: 0.875rem; 
            border-radius: var(--radius);
            padding: 1rem 1.25rem;
            border: none;
        }
        
        .alert-success {
            background: hsl(142 76% 96%);
            color: hsl(142 76% 16%);
        }
        
        .alert-danger {
            background: hsl(0 84% 96%);
            color: hsl(0 84% 16%);
        }
        
        .login-links {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }
        
        .login-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            margin: 0 1rem;
        }
        
        .login-links a:hover {
            color: hsl(from var(--primary) h s calc(l - 10%));
            text-decoration: underline;
        }
        
        @media (max-width: 768px) { 
            .registration-card { 
                padding: 1.5rem;
                margin: 1rem;
            }
            .registration-title {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <div class="registration-card">
            <div class="registration-title">Tenant Registration</div>
            <div class="registration-subtitle">Join our facility management system and access services online</div>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"> 
                    <?= session()->getFlashdata('success') ?> 
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"> 
                    <?= session()->getFlashdata('error') ?> 
                </div>
            <?php endif; ?>
            
            <form id="registrationForm" method="post" action="/tenant/register" autocomplete="off">
                <!-- Personal Information Section -->
                <div class="form-section-title">Personal Information</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address">
                        </div>
                    </div>
                </div>
                
                <!-- Property Information Section -->
                <div class="form-section-title">Property Information</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="property_id" class="form-label">Property <span class="text-danger">*</span></label>
                            <select class="form-select" id="property_id" name="property_id" required>
                                <option value="">Select Property</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="flat_office_no" class="form-label">Flat/Office Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="flat_office_no" name="flat_office_no" required>
                        </div>
                    </div>
                </div>
                
                <!-- Emergency Contact Section -->
                <div class="form-section-title">Emergency Contact</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="emergency_contact" class="form-label">Emergency Contact Name</label>
                            <input type="text" class="form-control" id="emergency_contact" name="emergency_contact">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="emergency_phone" class="form-label">Emergency Contact Phone</label>
                            <input type="tel" class="form-control" id="emergency_phone" name="emergency_phone">
                        </div>
                    </div>
                </div>
                
                <!-- Lease Information Section -->
                <div class="form-section-title">Lease Information</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="move_in_date" class="form-label">Move-in Date</label>
                            <input type="date" class="form-control" id="move_in_date" name="move_in_date">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lease_end_date" class="form-label">Lease End Date</label>
                            <input type="date" class="form-control" id="lease_end_date" name="lease_end_date">
                        </div>
                    </div>
                </div>
                
                <div class="row g-3 mt-3">
                    <div class="col-md-6">
                        <button type="submit" class="btn-enhanced btn-primary-enhanced w-100">Register</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn-enhanced btn-outline-enhanced w-100" onclick="resetForm()">Clear Form</button>
                    </div>
                </div>
            </form>
            
            <div class="login-links">
                <a href="/tenant/guest-login">Already a tenant? Login here</a>
                |
                <a href="/login">Staff Login</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Load properties
        function loadProperties() {
            $.get('/tenant/api/properties', function(properties) {
                let options = '<option value="">Select Property</option>';
                (properties || []).forEach(function(property) {
                    const displayName = property.building_name + (property.building_number ? ' - ' + property.building_number : '');
                    options += `<option value="${property.id}">${displayName}</option>`;
                });
                $('#property_id').html(options);
            }).fail(function() {
                console.log('Error loading properties');
                // Fallback options for testing
                $('#property_id').html(`
                    <option value="">Select Property</option>
                    <option value="1">Building A - 101</option>
                    <option value="2">Building B - 102</option>
                    <option value="3">Main Tower - 201</option>
                `);
            });
        }
        
        function resetForm() {
            document.getElementById('registrationForm').reset();
            $('#property_id').val('').trigger('change');
        }
        
        // Form validation
        $('#registrationForm').on('submit', function(e) {
            const name = $('#name').val().trim();
            const email = $('#email').val().trim();
            const phone = $('#phone').val().trim();
            const property = $('#property_id').val();
            const flatNo = $('#flat_office_no').val().trim();
            
            if (!name || !email || !phone || !property || !flatNo) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return false;
            }
            
            return true;
        });
        
        $(document).ready(function() {
            loadProperties();
            
            // Initialize Select2 for better dropdowns
            $('#property_id').select2({
                placeholder: 'Select Property',
                allowClear: true
            });
        });
    </script>
</body>
</html>
