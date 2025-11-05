<?php
include 'includes/session_check.php';
require_login(); 
flash_message();

include 'includes/header.php';
include 'includes/head.php';
include 'db_con.php';


$models = $pdo->query("SELECT DISTINCT Model FROM motorcycle ORDER BY Model ASC")->fetchAll();
$engineTypes = $pdo->query("SELECT DISTINCT Engine_type FROM motorcycle ORDER BY Engine_type ASC")->fetchAll();
?>

<main class="main">
    <section id="hero" class="hero section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="hero-wrapper">
                <div class="row g-4">

                    <div class="col-lg-7">
                        <div class="hero-content" data-aos="zoom-in" data-aos-delay="200">
                            <div class="content-header">
                                <span class="hero-label">
                                    <img src="assets/img/motorcycles/motorcycle.png"
                                        alt="Motorcycle Icon"
                                        style="width:22px; height:22px; vertical-align:middle; margin-right:6px;">
                                    Dream Motorcycles Await
                                </span>
                                <h1>Find Your Ideal Motorcycle with Expert Guidance</h1>
                                <p>Discover top motorcycle listings with trusted dealers. Filter by brand, model, or engine type to find your perfect ride.</p>
                            </div>


                            <div class="search-container" data-aos="fade-up" data-aos-delay="300">
                                <div class="search-header">
                                    <h3>Start Your Motorcycle Search</h3>
                                    <p>Discover thousands of verified motorcycles</p>
                                </div>

                              
                                <form action="motorcycle.php" method="GET" class="property-search-form">
                                    <div class="search-grid">
                                       
                                        <div class="search-field">
                                            <label for="search-brand" class="field-label">Brand</label>
                                            <input type="text" id="search-brand" name="brand" placeholder="Enter Brand name">
                                            <i class="bi bi-geo-alt field-icon"></i>
                                        </div>

                                    
                                        <div class="search-field">
                                            <label for="search-motorcycletype" class="field-label">Motorcycle Type</label>
                                            <select id="search-motorcycletype" name="motorcycletype">
                                                <option value="">All Types</option>
                                                <option value="sportbike">Sport Bike</option>
                                                <option value="cruiser">Cruiser</option>
                                                <option value="adventurebike">Adventure Bike</option>
                                                <option value="dirtbike">Dirt Bike</option>
                                                <option value="scooter">Scooter</option>
                                                <option value="nakedbike">Naked Bike</option>
                                                <option value="bobber">Bobber</option>
                                            </select>
                                            <i class="bi bi-building field-icon"></i>
                                        </div>

                                  
                                        <div class="search-field">
                                            <label for="search-model" class="field-label">Model</label>
                                            <select id="search-model" name="model">
                                                <option value="">All Models</option>
                                                <?php foreach ($models as $row): ?>
                                                    <option value="<?= htmlspecialchars($row['Model']) ?>"><?= htmlspecialchars($row['Model']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <i class="bi bi-currency-dollar field-icon"></i>
                                        </div>

                                      
                                        <div class="search-field">
                                            <label for="search-enginetype" class="field-label">Engine Type</label>
                                            <select id="search-enginetype" name="enginetype">
                                                <option value="">All Engine Types</option>
                                                <?php foreach ($engineTypes as $row): ?>
                                                    <option value="<?= htmlspecialchars($row['Engine_type']) ?>"><?= htmlspecialchars($row['Engine_type']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <i class="bi bi-door-open field-icon"></i>
                                        </div>
                                    </div>

                                    <button type="submit" class="search-btn">
                                        <i class="bi bi-search"></i>
                                        <span>Find Motorcycle</span>
                                    </button>
                                </form>
                            </div>

                            
                                <div class="achievement-item">
                                    <div class="achievement-number">
                                        <span data-purecounter-start="0" data-purecounter-end="1250" data-purecounter-duration="1" class="purecounter"></span>+
                                    </div>
                                    <span class="achievement-text">Active Listings</span>
                                </div>
                                <div class="achievement-item">
                                    <div class="achievement-number">
                                        <span data-purecounter-start="0" data-purecounter-end="89" data-purecounter-duration="1" class="purecounter"></span>+
                                    </div>
                                    <span class="achievement-text">Expert Dealers</span>
                                </div>
                                <div class="achievement-item">
                                    <div class="achievement-number">
                                        <span data-purecounter-start="0" data-purecounter-end="96" data-purecounter-duration="1" class="purecounter"></span>%
                                    </div>
                                    <span class="achievement-text">Customer Satisfaction</span>
                                </div>
                            </div> 
                        </div>
                    </div>

                    
                    <div class="col-lg-5">
                        <div class="hero-visual" data-aos="fade-left" data-aos-delay="400">
                            <div class="visual-container">
                                <div class="featured-property">
                                    <img src="assets/img/motorcycles/Gemini_Generated_Image_mxpmamxpmamxpmam.png" alt="Featured Motorcycle" class="img-fluid">
                                    <div class="property-info">
                                        <div class="property-details">
                                            <span><i class="bi bi-geo-alt"></i> Wakanda Philippines</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="agent-card">
                                    <div class="agent-profile">
                                        <img src="assets/img/real-estate/agent-7.webp" alt="Agent Profile" class="agent-photo">
                                        <div class="agent-info">
                                            <h4>John Bryan</h4>
                                            <p>Dealership Manager</p>
                                            <div class="agent-rating">
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                </div>
                                                <span class="rating-text">5.0 (94 reviews)</span>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="contact-agent-btn">
                                        <i class="bi bi-chat-dots"></i>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
