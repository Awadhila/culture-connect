<?php 
$pageTitle = "About Us - Culture Connect";
include 'includes/header.php'; 

// Expanded Dynamic Content
$mission_title = "Our Vision for Community Culture";
$mission_desc = "Culture Connect is a pioneering digital initiative designed to revitalize local community engagement through technology. We act as a bridge, connecting residents with the rich tapestry of local arts, music, and heritage. By providing creative SMEs with a high-visibility platform to showcase their talents, and giving councils the empirical data needed to lead, we ensure that cultural funding is allocated where it is valued most by the people.";

$stakeholders = [
    [
        "title" => "For Residents",
        "desc" => "Explore a curated selection of local art, music, and heritage events. Through our structured voting system, your voice directly influences the cultural landscape of your neighborhood. By participating, you ensure that the classes and performances you love stay funded and accessible.",
        "icon" => "bi-people"
    ],
    [
        "title" => "For Creative SMEs",
        "desc" => "Gain a dedicated platform to showcase your workshops, artisanal products, and performances. Culture Connect eliminates the barrier between independent creators and local audiences, helping you grow your business while contributing to the community's unique cultural identity.",
        "icon" => "bi-shop"
    ],
    [
        "title" => "For Councils & Partners",
        "desc" => "Harness the power of community-driven data. Our platform provides real-time insights into which cultural offerings residents prioritize. This evidence-based approach allows for more effective resource allocation and strategic planning for long-term cultural development."
    ]
];
?>

<main class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-4" style="font-size: 3.5rem; letter-spacing: -2px;">ABOUT<br><span style="color: #72b1e1;">US</span></h1>
                <div class="border-start border-4 border-dark ps-4">
                    <h3 class="fw-bold text-uppercase" style="letter-spacing: 1px;"><?php echo $mission_title; ?></h3>
                    <p class="about-mission-text fw-semibold text-justify">
                        <?php echo $mission_desc; ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach ($stakeholders as $item): ?>
            <div class="col-md-4">
                <div class="card h-100 border-4 border-dark rounded-0 shadow-none p-4 bg-white">
                    <h4 class="fw-bold text-uppercase mb-3" style="color: #72b1e1; letter-spacing: 1px;">
                        <?php echo $item['title']; ?>
                    </h4>
                    <p class="small mb-0 text-justify" style="line-height: 1.6;">
                        <?php echo $item['desc']; ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-5 p-5 bg-dark text-white border-4 border-dark">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h2 class="fw-bold mb-4" style="letter-spacing: 2px;">SHAPING OUR SHARED LANDSCAPE</h2>
                    <p class="text-justify mb-0 mx-auto" style="max-width: 800px;">
                        Culture Connect is more than a directory; it is a vital community tool for sustainable development. Every vote cast by a resident provides a data point that helps local councils justify funding for arts and heritage. By participating, you are directly supporting the economic viability of local artists and ensuring that cultural diversity remains a cornerstone of our community's future.
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>