// *********Index.html Page content starts here *********************
function loadContent(contentId) {
    var contentContainer = document.getElementById('content-container');

    var contentData = {
        home: `
<h2>myWelcome to the Home Page</h2>
<p>This is the homepage of the Trust website.</p>
`,
        schoolProfile: `
<div class="mid">
    <div class="Profilemid">
        <div class="profilemid1 toptext-2">
            <h2>Trust at a Glance</h2>
        </div>
        <div class="profilemid2">
            <div class="profilemid21">
                <img src="images/mid-2.jpg" height="510px" width="100%"alt="">
            </div>
            <div class="profilemid21">
                <p><b>Name of Trust: </b>Shri Siddhi Vinayak Educational Trust, Tibbi</p>

                <p><b>Trust Reg. No.: </b>3/2018/HANUMANGARH</p>

                <p><b>Establishment Date: </b>07-Mar-2018</p>

                <p><b>Year Established:</b> 2018</p>

                <p><b>12-AA Reg. No.: </b>CIT EXEMPTION, JAIPUR/12AA/2018-19/A/10291</p>

                <p><b>80-G Reg. No.: </b> CIT EXEMPTION, JAIPUR/80G/2020-21/A/0144</p>
                <p><b>NITI Aayog Reg. No.: </b> RJ/2019/0246688</p>
                <p><b>CSR activities and Reg. No.: </b> CSR00081170</p>

                <p><b> Postal Address: </b>Near Post office Tibbi, Dist.- Hanumangarh, Rajasthan, Pincode - 335526
                </p>

                <p><b> Telephones: </b>+91 94133-78652 </p>

                <p><b> E-Mail: </b>ssvet732018@gmail.com</p>

                <p><b> Website: </b>www.ssvtrust.com</p>
            </div>
        </div>
        <div class="profilemid3">
            <div class="profilemid31">
                <p><b>Foundation and Registration: </b> Shri Siddhi Vinayak Educational Trust was established on 7th March 2018 with a strong commitment to promoting education and social welfare. The Trust is formally registered under the Rajasthan Trust Act, 1959 (42) and operates from Tibbi, Hanumangarh, Rajasthan. </p>
                <p><b>Leadership:  </b> The Trust is led by Shri Gauri Shankar Kalyani, whose vision and dedication have been instrumental in shaping its objectives and initiatives. Under his guidance, the Trust has focused on addressing the educational needs of underprivileged communities, aiming to make quality education accessible to all. </p>
                <p><b>Mission and Objectives: </b>  Shri Siddhi Vinayak Educational Trust is dedicated to providing educational opportunities to economically disadvantaged groups. The Trust's primary objective is to bridge the education gap by running schools and offering quality education to children from financially challenged backgrounds. The Trust's mission is grounded in the belief that education is a powerful tool for social change and empowerment. </p>
            </div>
        </div>
        <div class="profilemid2">
            <div class="profilemid21">
                <h3 class="textcenter">Our Directions</h3>
                <iframe class ="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3470.7716898968897!2d74.50246451510365!3d29.552142482064145!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39169479fadf422b%3A0xc5fa9af2b5fbb9d!2sShri+Siddhi+Vinayak+Shikshan+Sansthan!5e0!3m2!1sen!2sin!4v1541841188215"></iframe>
                </div>
                <div class="profilemid21" "newsletter">
                <h3 class="textcenter">Opening Hours</h3>
                <p class="textcenter"><b>Mon-Sat : </b> 8 AM to 5 PM </p>
                <p class="textcenter"><b>Sunday : </b> Closed </p>
                <div class="newsletter">
					<h3 class="textcenter">Subscribe Our Newsletter</h3>
					<form action="#" method="post" class="textcenter">
						<input class="" placeholder="Enter your email..." required="">
						<button type="submit" class="submit">Submit</button>
					</form>
				</div>
            </div>
        </div>
    </div>
</div>
`,
        visionMission: `
<div class="mid">
<div class="visionmissionmid">
<div class="visionmissionmid-1">
<!-- Vision Section -->
<div class="visionmid">

<b class="toptext-2">Vision</b> 
<p class="visiontext">
To be a leading and transformative educational institution, inspiring lifelong learning and empowering students to become confident, compassionate, and successful individuals, ready to make a positive impact on the world.
</p>
</div>
<!-- Mission Section -->
<div class="missionmid">

<b class="toptext-2">Mission</b> 
<p class="missiontext">
Our mission is to provide a nurturing and inclusive learning environment where students thrive academically, emotionally, and socially. We are committed to delivering a well-rounded education that fosters critical thinking, creativity, and a passion for lifelong learning. Through innovative teaching methods, dedicated educators, and strong partnerships with parents and the community, we aim to equip our students with the knowledge, skills, and values necessary for personal growth and global citizenship.
</p>
</div>
</div>
</div>
</div>
`,
        principalMessage: `
<div class="indexmid">
<div class="indexmid2">
<div class="indexmid21">
<div class="directorphoto">
<img src="images/Principal.jpeg" alt="Principal Photo">
</div>
</div>
<div class="indexmid22">
<div class="directormessage">
<img src="images/Pointer.png" alt="">
<b class="toptext-3">Mr. Punit Kumar Kalyani</b><BR>
<p class ="message">
<strong>Dear Parents/Guardians,</strong>
<br />
It gives me immense pleasure to welcome all the students of Under-graduate courses. Our trust, enjoying a total autonomous status for the last several years. I am duty-bound to thank the Management, the Staff and the Students for these achievements.
<br />
<br />
The students must exhibit exemplary behavior even outside the trust without getting themselves provoked or letting themselves be taken for a ride especially while travelling. Directly or indirectly, knowingly or unknowingly, they should in no way bring disrepute to the college of their study. I am sure our students will always strive to uphold the dignity of our college and keep its flag ever flying high. India is known for its rich values, culture and heritage. I impress upon all our young learning community to emerge as her proud and worthy citizens.
<br />
<br />
<strong> I wish our students all success in their endeavors.</strong>
</p>
</div>
</div>
</div>
</div>
</div>
`,
        directorMessage: `
<div class="indexmid">
<div class="indexmid2">
<div class="indexmid21">
<div class="directorphoto">
<img src="images/director.jpeg" alt="Director's Photo">
</div>
</div>
<div class="indexmid22">
<div class="directormessage">
<img src="images/Pointer.png" alt=""><br>
<b class="toptext-3">Mr. Gauri Shankar Kalyani</b><BR>
<p class ="message">
<p class="mt-3 mb-4 pr-lg-5 text-center">
<strong>Dear Parents/Guardians,</strong>
<br>
Shri Siddhi Vinayak Educational Trust has always been at the forefront of education in India in terms of quality of higher education. This has been made possible with the blessings of our elders, visionary members of the school managing committee, supportive parents and dedicated staff who have relentlessly strived to deliver quality education.
<br>
<br>
Since its inception, we have always been committed to providing high quality of higher education that emphasizes not only gaining knowledge but also focuses on the student's holistic development.
<br>
<br>
For its continuous improvement, we listen and respond to parent's concerns and needs of a changing society. Our child-centered approach to teaching and learning at the trust level has become successful in terms of the overall development of the children.
<br>
<br>
We have infrastructure of an international standard supported by state-of-the-art facilities, and a team of professionals to nurture the creative minds of students. We reiterate our commitment to provide a nurturing environment and every facility possible to ensure a high quality education every student deserves in today's globalized world.
</p>
</p>
</div>
</div>
</div>
</div>
<div id="footer"></div> <!-- Footer will load here -->
</div>
`,
        management: `
<div class="mid">
<!-- Photo Gallery Section -->
<section class="gallery">
<h2>Management</h2>
<div class="gallery-grid">
<div class="gallery-item">
<img src="images/director.jpeg" alt="Management Image 1" onclick="openLightbox(0, 'image')">
<p class="image-title">Mr. Gauri Shankar Kanyani <br> (Director)<Title></Title></p>
</div>
<div class="gallery-item">
<img src="images/principal.jpeg" alt="Management Image 2" onclick="openLightbox(1, 'image')">
<p class="image-title">Mr. Punit Kalyani <br> (Principal)<Title></Title></p>
</div>
<div class="gallery-item">
<img src="images/Treasurer.jpeg" alt="Management Image 3" onclick="openLightbox(2, 'image')">
<p class="image-title">Mr. Seeta Ram Kalyani <br> (Treasurer)<Title></Title></p>
</div>
<div class="gallery-item">
<img src="images/PNA.jfif" alt="Management Image 4" onclick="openLightbox(3, 'image')">
<p class="image-title">Mrs. Durga Devi <br> (Member) <Title></Title></p>
</div>
<div class="gallery-item">
<img src="images/PNA.jfif" alt="Management Image 5" onclick="openLightbox(4, 'image')">
<p class="image-title">Mrs. Mr. Sunit Devi <br> (Member)<Title></Title></p>
</div>
</div>
</section>

 <!-- Management Structure -->
    <section class="management-structure">
        <h2>Management Structure</h2>
        <div class="organization-chart">
            <div class="org-level">
                <div class="org-box primary">
                    <h3>Board of Trustees</h3>
                    <p>Oversees the trust's operations and strategic direction</p>
                </div>
            </div>
            <div class="org-level">
                <div class="org-box secondary">
                    <h3>Director</h3>
                    <p>Mr. Gauri Shankar Kalyani</p>
                </div>
            </div>
            <div class="org-level">
                <div class="org-box tertiary">
                    <h3>Principal</h3>
                    <p>Mr. Punit Kalyani</p>
                </div>
            </div>
            <div class="org-level multi">
                <div class="org-box quaternary">
                    <h3>Administrative Staff</h3>
                    <p>Manages daily operations</p>
                </div>
                <div class="org-box quaternary">
                    <h3>Teaching Faculty</h3>
                    <p>Delivers educational curriculum</p>
                </div>
                <div class="org-box quaternary">
                    <h3>Support Staff</h3>
                    <p>Provides essential services</p>
                </div>
            </div>
        </div>
    </section>
`,

// Financial section HTML Template
financialsdfk:`
<div class="financials-section">
    <h3 class="financials-title">Financials</h3>
    <div class="financials-grid">
        <div class="financial-card">
            <div class="financial-type">AUDIT REPORT</div>
            <div class="financial-year">2023-24</div>
            <a href="documents//AUDIT REPORT.pdf" target="_blank" class="pdf-link">
                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="PDF Icon" style="width:40px; height:40px;">

            </a>
        </div>

        <div class="financial-card">
            <div class="financial-type">AUDIT REPORT</div>
            <div class="financial-year">2022-23</div>
            <a href="documents/AUDIT REPORT 2022-23.pdf" target="_blank" class="pdf-link">
                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="PDF Icon" style="width:40px; height:40px;">
            </a>
        </div>

        <div class="financial-card">
            <div class="financial-type">ITR</div>
            <div class="financial-year">2020-21</div>
            <a href="documents/ITR 2020-21.pdf" target="_blank" class="pdf-link">
                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="PDF Icon" style="width:40px; height:40px;">
            </a>
        </div>

        <div class="financial-card">
            <div class="financial-type">ITR</div>
            <div class="financial-year">2019-20</div>
            <a href="documents/ITR 2019-20.pdf" target="_blank" class="pdf-link">
                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="PDF Icon" style="width:40px; height:40px;">
            </a>
        </div>

        <div class="financial-card">
            <div class="financial-type">ITR</div>
            <div class="financial-year">2018-19</div>
            <a href="documents/ITR 2018-19.pdf" target="_blank" class="pdf-link">
                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="PDF Icon" style="width:40px; height:40px;">
            </a>
        </div>
    </div>
</div>
`,

financials:`
<div class="financials-section">
    <h2 class="financialmid">Financials</h2>
    <div id="financialsContainer" style="text-align: center; padding: 40px;">
        <p style="color: #999;">
            <i class="fas fa-spinner fa-spin"></i> Loading financial documents...
        </p>
    </div>
</div>`,

// Faculty Section HTML Template
        faculties: `
       <div class="mid">
                <section class="faculty">
                    <h2 class="toptext-2">The Team Behind Our Success</h2>
                    <div class="faculty-cards">
                        <div id="facultyContainer"></div>
                    </div>
                </section>
            </div>`,
// Toppers Section HTML Template
        toppers: `
         <div class="mid">
    <section class="toppers">
      <h2 class="toptext-2">Our Stars</h2>
      <!-- Session buttons will be injected here -->
      <div id="sessionsContainer" class="sessions-container"></div>
      <!-- Toppers cards will be injected here -->
      <div class="toppers-cards">
        <div id="toppersContainer"></div>
      </div>
    </section>
  </div>
  `,

        timeTable: `
<!-- content will be fetched dynamically from time_table.html -->
        `,
        syllabus: `
<!-- content will be fetched dynamically from syllabus.html -->
`,
        photoGallery: `
<div class="mid">
<!-- Photo Gallery Section -->
<section class="gallery">
<h2>School Photo Gallery</h2>
<div class="gallery-grid">
<div class="gallery-item">
<img src="images/Event1.jpg" alt="School Image 1" onclick="openLightbox(0, 'image')">
</div>
<div class="gallery-item">
<img src="images/Event2.jpg" alt="School Image 2" onclick="openLightbox(1, 'image')">
</div>
<div class="gallery-item">
<img src="images/Event3.jpg" alt="School Image 3" onclick="openLightbox(2, 'image')">
</div>
<div class="gallery-item">
<img src="images/Event4.jpg" alt="School Image 4" onclick="openLightbox(3, 'image')">
</div>
<div class="gallery-item">
<img src="images/Event5.jpg" alt="School Image 5" onclick="openLightbox(4, 'image')">
</div>
<div class="gallery-item">
<img src="images/Event6.jpg" alt="School Image 6" onclick="openLightbox(5, 'image')">
</div>
<div class="gallery-item">
<img src="images/Event7.jpg" alt="School Image 7" onclick="openLightbox(6, 'image')">
</div>
<div class="gallery-item">
<img src="images/Event8.jpg" alt="School Image 8" onclick="openLightbox(7, 'image')">
</div>
<div class="gallery-item">
<img src="images/Event9.jpg" alt="School Image 9" onclick="openLightbox(8, 'image')">
</div>
<div class="gallery-item">
<img src="images/Event10.jpg" alt="School Image 10" onclick="openLightbox(9, 'image')">
</div>
</div>
</section>

<!-- Lightbox Container for Images -->
<div id="lightbox" class="lightbox">
<span class="close" onclick="closeLightbox()">&times;</span>
<span class="prev" onclick="changeMedia(-1)">&#10094;</span>
<div id="lightbox-content" class="lightbox-content"></div>
<span class="next" onclick="changeMedia(1)">&#10095;</span>
</div>
`,
        videoGallery: `
<div class="mid">
<!-- Video Gallery Section -->
<section class="video-gallery">
<h2>School Video Gallery</h2>
<div class="video-grid">
<div class="video-item">
<img src="https://img.youtube.com/vi/c5I199uvV2E/0.jpg" alt="Video Thumbnail 1" onclick="openLightbox(0, 'video')" data-video-id="c5I199uvV2E">
<p class="video-title">L-3 Sanskrit Class-6th Lesson-1 Part-1 By Heeramani Bhatia <Title></Title></p>
</div>
<div class="video-item">
<img src="https://img.youtube.com/vi/bdbEGwtyxWA/0.jpg" alt="Video Thumbnail 2" onclick="openLightbox(1, 'video')" data-video-id="bdbEGwtyxWA">
<p class="video-title">L-5 President - Polity Part-4 By Hardeep Singh <Title></Title></p>
</div>
<div class="video-item">
<img src="https://img.youtube.com/vi/8AW2zrBYjBw/0.jpg" alt="Video Thumbnail 3" onclick="openLightbox(2, 'video')" data-video-id="8AW2zrBYjBw">
<p class="video-title">L-2 Set in Maths Part-1 Class-11th By Vinod Kumar Suthar<Title></Title></p>
</div>
<div class="video-item">
<img src="https://img.youtube.com/vi/xRdStHvg5-o/0.jpg" alt="Video Thumbnail 4" onclick="openLightbox(3, 'video')" data-video-id="xRdStHvg5-o">
<p class="video-title">L 2 आक्षांश व देशांतर Geography By Rekha Suthar<Title></Title></p>
</div>
<div class="video-item">
<img src="https://img.youtube.com/vi/0smgsj8wGkQ/0.jpg" alt="Video Thumbnail 5" onclick="openLightbox(4, 'video')" data-video-id="0smgsj8wGkQ">
<p class="video-title">L-6 Voice Part 1 AND 2 By Madan Ji Saharan <Title></Title></p>
</div>
<div class="video-item">
<img src="https://img.youtube.com/vi/oGYtYt8BKWg/0.jpg" alt="Video Thumbnail 6" onclick="openLightbox(5, 'video')" data-video-id="oGYtYt8BKWg">
<p class="video-title">L-4 Alcohol Part 4 - Preparation Method Of Alcohol By Akhtar Ali <Title></Title></p>
</div>
<div class="video-item">
<img src="https://img.youtube.com/vi/5meDiZ7PfhY/0.jpg" alt="Video Thumbnail 7" onclick="openLightbox(6, 'video')" data-video-id="5meDiZ7PfhY">
<p class="video-title">L-3 Verb (English) By Paramjeet Kaur <Title></Title></p>
</div>
<div class="video-item">
<img src="https://img.youtube.com/vi/ol6cd37mKxk/0.jpg" alt="Video Thumbnail 8" onclick="openLightbox(7, 'video')" data-video-id="ol6cd37mKxk">
<p class="video-title">L-3 Percentage Part 3 in Maths By Mukesh Kumar</p>
</div>
<div class="video-item">
<img src="https://img.youtube.com/vi/E_IwgcF34Ik/0.jpg" alt="Video Thumbnail 9" onclick="openLightbox(8, 'video')" data-video-id="E_IwgcF34Ik">
<p class="video-title">L-3 काल (Tense in Hindi) Part-2 By Pradeep Sharma</p>
</div>
<div class="video-item">
<img src="https://img.youtube.com/vi/yTFnkTTWHT4/0.jpg" alt="Video Thumbnail 10" onclick="openLightbox(9, 'video')" data-video-id="yTFnkTTWHT4">
<p class="video-title">L 3 भारतीय इतिहास के स्त्रोत Part 2 History Class 12th By Prem Singh Gill</p>
</div>
</div>
</section>

<!-- Lightbox Container for Videos -->
<div id="lightbox" class="lightbox">
<span class="close" onclick="closeLightbox()">&times;</span>
<span class="prev" onclick="changeMedia(-1)">&#10094;</span>
<div id="lightbox-content" class="lightbox-content"></div>
<span class="next" onclick="changeMedia(1)">&#10095;</span>
</div>
<div id="footer"></div> <!-- Footer will load here -->
</div>
`,
        rteOverview: `
        <div class="indexmid">
            <!-- <h1>Right to Education (RTE) Overview</h1> -->
            <div class="rte">
                <p>
                    The <strong>Right to Education (RTE) Act</strong>, enacted in 2009, is a landmark legislation in
                    India that ensures free and compulsory education for children aged 6 to 14 years. The RTE Act
                    upholds the constitutional guarantee under Article 21A, aiming to provide quality education for all
                    children regardless of their socio-economic background.
                </p>

                <h2>Key Features of the RTE Act:</h2>
                <ul>
                    <li>Free and compulsory education for all children aged 6 to 14 years.</li>
                    <li>Reservation of 25% seats in private schools for underprivileged children.</li>
                    <li>Prohibition of capitation fees, donation-based admissions, and physical punishment.</li>
                    <li>Focus on infrastructure, including adequate classrooms, qualified teachers, and learning
                        resources.</li>
                    <li>Establishment of norms and standards for schools to ensure quality education.</li>
                </ul>

                <h2>How RTE is Implemented:</h2>
                <p>
                    Schools, including government, aided, and private institutions, are mandated to comply with the
                    provisions of the RTE Act. The act also empowers parents, communities, and local authorities to play
                    an active role in ensuring every child’s right to education is upheld.
                </p>

                <h2>Benefits for the Community:</h2>
                <p>
                    The RTE Act has significantly contributed to increasing school enrollments, reducing dropout rates,
                    and bridging educational disparities across regions and communities. It ensures that education
                    becomes a tool for empowerment and socio-economic development for all children.
                </p>

                <p>
                    At <strong>Shri Siddhi Vinayak School</strong>, we are committed to implementing the RTE Act with utmost
                    dedication. We ensure that every eligible child is provided access to quality education and equal
                    opportunities for growth and development. For more details, please visit our administration office
                    or contact us.
                </p>

                <p>
                    Together, let's build a future where education is a right, not a privilege.
                </p>
            </div>


        </div>
`,
        rtePolicy: `
 <div class="indexmid">
            <h1>Right to Education (RTE) Policy</h1>
            <div class="rte">
                <p>
                    The <strong>Right to Education (RTE) Policy</strong> lays out the framework for the implementation of the RTE Act at Nosegay Public School. It ensures equitable access to quality education for all eligible children, particularly from underprivileged backgrounds.
                </p>
            
                <h2>Key Provisions of the RTE Policy:</h2>
                <ul>
                    <li><strong>Admission Policy:</strong> 25% of seats are reserved for children from economically weaker sections (EWS) and disadvantaged groups in accordance with the RTE Act.</li>
                    <li><strong>No Screening Process:</strong> Admissions are granted without any form of screening or interview for both children and parents.</li>
                    <li><strong>Prohibition of Fees:</strong> No capitation fees or donations are charged during admission.</li>
                    <li><strong>Age-Appropriate Learning:</strong> Children are admitted to classes appropriate to their age to ensure a smooth transition into formal education.</li>
                    <li><strong>Inclusive Education:</strong> Special provisions are made for children with disabilities to ensure access to education without barriers.</li>
                </ul>
            
                <h2>Steps for Admission Under RTE:</h2>
                <ol>
                    <li>Parents must fill out the RTE application form, available on the school website or at the administration office.</li>
                    <li>Submit necessary documents, including proof of residence, income certificate, and child’s birth certificate.</li>
                    <li>Eligible applications are verified by the school administration based on the guidelines provided by the RTE Act.</li>
                    <li>Seats are allocated based on the availability and compliance with the reservation quota.</li>
                </ol>
            
                <h2>Documents Required for RTE Admission:</h2>
                <ul>
                    <li>Child’s birth certificate</li>
                    <li>Proof of residence (e.g., Aadhaar card, ration card, voter ID)</li>
                    <li>Income certificate for economically weaker sections (EWS)</li>
                    <li>Caste certificate (if applicable)</li>
                    <li>Disability certificate (if applicable)</li>
                </ul>
            
                <h2>Compliance and Monitoring:</h2>
                <p>
                    The school regularly monitors and evaluates the implementation of the RTE Policy to ensure compliance with government regulations. Transparency and accountability are maintained at all stages of the admission and education process.
                </p>
            
                <p>
                    For further details or assistance regarding the RTE Policy, parents and guardians can contact the school administration. Let us work together to uphold the right to education for every child.
                </p>
            </div>
            
            <div class="rte">
                <h1>आरटीई (शिक्षा का अधिकार) नीति</h1>
                <p>
                    <strong>आरटीई (Right to Education) अधिनियम</strong> 2009 में भारत सरकार द्वारा लागू किया गया था। यह अधिनियम 
                    संविधान के अनुच्छेद 21ए के तहत 6 से 14 वर्ष तक के बच्चों को मुफ्त और अनिवार्य शिक्षा का अधिकार प्रदान करता है। 
                    इसका उद्देश्य सभी बच्चों को समान शिक्षा का अवसर प्रदान करना और सामाजिक-आर्थिक असमानताओं को समाप्त करना है।
                </p>
            
                <h2>आरटीई नीति के मुख्य प्रावधान:</h2>
                <ul>
                    <li><strong>नि:शुल्क और अनिवार्य शिक्षा:</strong>  
                        6 से 14 वर्ष के बच्चों को नि:शुल्क शिक्षा प्रदान करना सभी सरकारी और सहायता प्राप्त स्कूलों का दायित्व है।
                    </li>
                    <li><strong>निजी स्कूलों में आरक्षण:</strong>  
                        आर्थिक रूप से कमजोर वर्गों (EWS) और वंचित समूहों के बच्चों के लिए 25% सीटें आरक्षित होती हैं। 
                        इन बच्चों की फीस सरकार द्वारा वहन की जाती है।
                    </li>
                    <li><strong>कैपिटेशन फीस और स्क्रीनिंग का प्रतिबंध:</strong>  
                        स्कूलों में दान या कैपिटेशन फीस नहीं ली जाएगी। प्रवेश के लिए बच्चों और अभिभावकों का कोई 
                        साक्षात्कार या स्क्रीनिंग नहीं की जाएगी।
                    </li>
                    <li><strong>उम्र के अनुसार कक्षा में प्रवेश:</strong>  
                        बच्चों को उनकी उम्र के अनुसार उपयुक्त कक्षा में प्रवेश दिया जाएगा ताकि वे औपचारिक शिक्षा 
                        में सहजता से प्रवेश कर सकें।
                    </li>
                    <li><strong>समावेशी शिक्षा:</strong>  
                        दिव्यांग बच्चों और विशेष आवश्यकताओं वाले बच्चों के लिए विशेष प्रावधान किए गए हैं ताकि उन्हें 
                        किसी प्रकार की बाधा के बिना शिक्षा प्राप्त हो सके।
                    </li>
                </ul>
            
                <h2>आरटीई के तहत प्रवेश की प्रक्रिया:</h2>
                <ol>
                    <li>अभिभावक आरटीई आवेदन पत्र भर सकते हैं, जो स्कूल की वेबसाइट या प्रशासन कार्यालय से उपलब्ध होगा।</li>
                    <li>आवश्यक दस्तावेज, जैसे निवास प्रमाण पत्र, आय प्रमाण पत्र, और बच्चे का जन्म प्रमाण पत्र, जमा करें।</li>
                    <li>आरटीई अधिनियम के दिशा-निर्देशों के अनुसार स्कूल प्रशासन द्वारा पात्र आवेदनों का सत्यापन किया जाएगा।</li>
                    <li>आरक्षण कोटा और सीट की उपलब्धता के आधार पर प्रवेश दिया जाएगा।</li>
                </ol>
            
                <h2>आरटीई प्रवेश के लिए आवश्यक दस्तावेज:</h2>
                <ul>
                    <li>बच्चे का जन्म प्रमाण पत्र</li>
                    <li>निवास प्रमाण पत्र (जैसे आधार कार्ड, राशन कार्ड, वोटर आईडी)</li>
                    <li>आय प्रमाण पत्र (आर्थिक रूप से कमजोर वर्गों के लिए)</li>
                    <li>जाति प्रमाण पत्र (यदि लागू हो)</li>
                    <li>दिव्यांग प्रमाण पत्र (यदि लागू हो)</li>
                </ul>
            
                <h2>अनुपालन और निगरानी:</h2>
                <p>
                    स्कूल नियमित रूप से आरटीई नीति के कार्यान्वयन की निगरानी और मूल्यांकन करता है ताकि सरकारी नियमों का पालन 
                    सुनिश्चित हो सके। प्रवेश और शिक्षा प्रक्रिया के सभी चरणों में पारदर्शिता और जवाबदेही बनाए रखी जाती है।
                </p>
            
                <p>
                    आरटीई नीति से संबंधित अधिक जानकारी या सहायता के लिए, अभिभावक स्कूल प्रशासन से संपर्क कर सकते हैं। 
                    आइए, हर बच्चे के शिक्षा के अधिकार को सुनिश्चित करने के लिए मिलकर काम करें।
                </p>
            </div>
            
        </div>

`,
        timeFrame: `
<div class="rtetimeframe">
<div class="rtemid">
<h2 class="rtemid">RTE Time Frame</h2>
<p><a href="documents/rte.pdf"><strong>Click Here</strong></a> to Download the Time Frame Circular.</p>
</div>
<div class="pdf-viewer">
<iframe src="documents/rte.pdf"></iframe>
</div>
</div>
`,
        rteFAQs: `
    <div class="rtetimeframe">
        <div class="rtemid">
            <h2 class="rtemid">RTE Time Frame</h2>
            <p><a href="documents/RTEFAQHindi.pdf"><strong>Click Here</strong></a> to Download the FAQs in Hindi.</p>
            <p><a href="documents/RTEFAQEnglish.pdf"><strong>Click Here</strong></a> to Download the FAQs in English.</p>
            </div>
            <div class="pdf-viewer">
            <div class="pdf-viewer RTEFAQHindi">
            <iframe src="documents/RTEFAQHindi.pdf"></iframe>
            </div>
            <div class="pdf-viewer RTEFAQEnglish">
            <iframe src="documents/RTEFAQEnglish.pdf"></iframe>
            </div>
        </div>
    </div>

`,
        rteHelpline: `
   <div class="indexmid">
   <h1>Contact Helpline</h1>
            <div class="rte">
                    <p>
                        If you have any questions, concerns, or complaints regarding the Right to Education (RTE) policy, you can reach out to the following helplines for assistance.
                    </p>
            
                    <h2>Government RTE Helpline</h2>
                    <ul>
                        <li><strong>Rajasthan RTE Helpline (Jaipur):</strong>  0141-2719073 (Toll-Free)</li>
                        <li><strong>State Education Department:</strong> Visit your respective state's education department website for state-specific helplines.</li>
                        <li><strong>Email:</strong> <a href="mailto:rajpsphelp.gmail.com">rajpsphelp[at]gmail[dot]com</a></li>
                    </ul>
            
                    <h2>Complaint Redressal Mechanism</h2>
                    <p>
                        For complaints related to RTE implementation, school admissions, or any grievances, you can use the following mechanisms:
                    </p>
                    <ul>
                        <li>
                            <strong>Online Grievance Portal:</strong> <a href="https://rajpsp.nic.in/PSP1/Home/HelpCenter.aspx" target="_blank">https://rajpsp.nic.in/PSP1/Home/HelpCenter.aspx</a>
                        </li>
                        <li>
                            <strong>District Education Office:</strong> Contact your district education officer for filing complaints at the local level.
                        </li>
                        <li>
                            <strong>Grievance Redressal Email:</strong> <a href="mailto:rajpsphelp[at]gmail[dot]com">rajpsphelp.gmail.com</a>
                        </li>
                    </ul>
            
                    <h2>School-Specific Helpline</h2>
                    <p>
                        If you have any issues or concerns specifically related to our school, please use the following contact details:
                    </p>
                    <ul>
                        <li><strong>School Helpline Number:</strong> +91-9413378652, +91-9610144744</li>
                        <li><strong>Email:</strong> <a href="mailto:sidhivinaysk552@gmail.com">sidhivinayak552@gmail.com</a></li>
                        <li><strong>Office Timings:</strong> School Working Days, 9:00 AM - 5:00 PM</li>
                    </ul>
            
                    <h2>Escalation Matrix</h2>
                    <p>
                        For unresolved issues, please escalate to the following contacts:
                    </p>
                    <ul>
                        <li><strong>School Principal:</strong> principal@ssvsst.com</li>
                        <li><strong>RTE Coordinator:</strong> sidhivinayak552@gmail.com</li>
                    </ul>
            
                    <p>
                        Your concerns are important to us, and we are here to assist you. Please do not hesitate to reach out for support.
                    </p>
                </div>                      
        </div>
`,
        careers:`
        <!-- content will be fetched dynamically from careers.html -->
        `,

        careers1: `
<div class="mid">
    <div class="careers-page">
        <h2>Career Opportunities</h2>
        
        <div class="careers-banner">
            <div class="banner-content">
                <h3>Join Our Team</h3>
                <p>Be a part of our mission to provide quality education</p>
                <a href="#current-openings" class="banner-btn">View Openings</a>
            </div>
        </div>
        
        <div class="careers-intro">
            <p>At Shri Siddhi Vinayak Educational Trust, we believe that our faculty and staff are our greatest assets. We are committed to recruiting and retaining talented individuals who are passionate about education and dedicated to nurturing the potential of every student.</p>
            <p>We offer a dynamic and supportive work environment where you can grow professionally while making a meaningful impact on the lives of our students. Join us in our mission to provide holistic education that empowers students to become responsible global citizens.</p>
        </div>
        
        <div class="why-join-us">
            <h3>Why Join Us?</h3>
            
            <div class="benefits-grid">
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h4>Professional Growth</h4>
                    <p>Regular workshops, training programs, and professional development opportunities to enhance your skills and knowledge</p>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4>Collaborative Environment</h4>
                    <p>Work with a team of dedicated professionals who are committed to excellence in education</p>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4>Career Advancement</h4>
                    <p>Clear pathways for career progression based on performance and commitment</p>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <h4>Meaningful Impact</h4>
                    <p>Opportunity to shape the future of young minds and make a positive difference in society</p>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <h4>Competitive Remuneration</h4>
                    <p>Attractive salary packages commensurate with qualifications and experience</p>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h4>Work-Life Balance</h4>
                    <p>Supportive policies to help you maintain a healthy balance between professional and personal life</p>
                </div>
            </div>
        </div>
        
        <div class="current-openings" id="current-openings">
            <h3>Current Openings</h3>
            
            <div class="openings-list">
                <div class="opening-item">
                    <div class="opening-header">
                        <div class="opening-title">
                            <h4>Mathematics Teacher</h4>
                            <span class="opening-badge">Full-Time</span>
                        </div>
                        <div class="opening-deadline">
                            <i class="fas fa-calendar-alt"></i> Application Deadline: June 30, 2023
                        </div>
                    </div>
                    <div class="opening-details">
                        <div class="opening-requirements">
                            <h5>Requirements:</h5>
                            <ul>
                                <li>Master's degree in Mathematics</li>
                                <li>B.Ed. degree from a recognized university</li>
                                <li>Minimum 3 years of teaching experience in secondary classes</li>
                                <li>Strong communication skills and proficiency in English</li>
                                <li>Knowledge of CBSE curriculum and teaching methodologies</li>
                            </ul>
                        </div>
                        <div class="opening-responsibilities">
                            <h5>Responsibilities:</h5>
                            <ul>
                                <li>Teaching Mathematics to secondary classes (9th to 12th)</li>
                                <li>Preparing lesson plans and instructional materials</li>
                                <li>Conducting assessments and evaluating student performance</li>
                                <li>Providing remedial support to students who need additional help</li>
                                <li>Participating in school events and extracurricular activities</li>
                            </ul>
                        </div>
                        <a href="#application-form" class="apply-btn">Apply Now</a>
                    </div>
                </div>
                
                <div class="opening-item">
                    <div class="opening-header">
                        <div class="opening-title">
                            <h4>English Language Teacher</h4>
                            <span class="opening-badge">Full-Time</span>
                        </div>
                        <div class="opening-deadline">
                            <i class="fas fa-calendar-alt"></i> Application Deadline: July 15, 2023
                        </div>
                    </div>
                    <div class="opening-details">
                        <div class="opening-requirements">
                            <h5>Requirements:</h5>
                            <ul>
                                <li>Master's degree in English Literature or Linguistics</li>
                                <li>B.Ed. degree from a recognized university</li>
                                <li>Minimum 2 years of teaching experience</li>
                                <li>Excellent command over spoken and written English</li>
                                <li>Experience in organizing literary activities and events</li>
                            </ul>
                        </div>
                        <div class="opening-responsibilities">
                            <h5>Responsibilities:</h5>
                            <ul>
                                <li>Teaching English language and literature to middle and high school students</li>
                                <li>Developing and implementing engaging lesson plans</li>
                                <li>Conducting regular assessments and providing constructive feedback</li>
                                <li>Organizing literary activities such as debates, elocution, and creative writing workshops</li>
                                <li>Mentoring students for language improvement</li>
                            </ul>
                        </div>
                        <a href="#application-form" class="apply-btn">Apply Now</a>
                    </div>
                </div>
                
                <div class="opening-item">
                    <div class="opening-header">
                        <div class="opening-title">
                            <h4>Computer Science Teacher</h4>
                            <span class="opening-badge">Full-Time</span>
                        </div>
                        <div class="opening-deadline">
                            <i class="fas fa-calendar-alt"></i> Application Deadline: July 10, 2023
                        </div>
                    </div>
                    <div class="opening-details">
                        <div class="opening-requirements">
                            <h5>Requirements:</h5>
                            <ul>
                                <li>Master's degree in Computer Science/IT or equivalent</li>
                                <li>B.Ed. degree (preferred)</li>
                                <li>Minimum 2 years of teaching experience in high school</li>
                                <li>Proficiency in programming languages (Python, Java)</li>
                                <li>Knowledge of web development and database management</li>
                            </ul>
                        </div>
                        <div class="opening-responsibilities">
                            <h5>Responsibilities:</h5>
                            <ul>
                                <li>Teaching Computer Science to high school students (9th to 12th)</li>
                                <li>Managing the computer lab and ensuring proper maintenance of equipment</li>
                                <li>Developing practical exercises and projects for hands-on learning</li>
                                <li>Organizing technical workshops and coding competitions</li>
                                <li>Staying updated with the latest technological advancements</li>
                            </ul>
                        </div>
                        <a href="#application-form" class="apply-btn">Apply Now</a>
                    </div>
                </div>
                
                <div class="opening-item">
                    <div class="opening-header">
                        <div class="opening-title">
                            <h4>Administrative Assistant</h4>
                            <span class="opening-badge">Full-Time</span>
                        </div>
                        <div class="opening-deadline">
                            <i class="fas fa-calendar-alt"></i> Application Deadline: June 25, 2023
                        </div>
                    </div>
                    <div class="opening-details">
                        <div class="opening-requirements">
                            <h5>Requirements:</h5>
                            <ul>
                                <li>Bachelor's degree in any discipline</li>
                                <li>Minimum 2 years of administrative experience, preferably in an educational institution</li>
                                <li>Proficiency in MS Office (Word, Excel, PowerPoint)</li>
                                <li>Excellent organizational and communication skills</li>
                                <li>Knowledge of school management software (preferred)</li>
                            </ul>
                        </div>
                        <div class="opening-responsibilities">
                            <h5>Responsibilities:</h5>
                            <ul>
                                <li>Managing daily administrative operations of the school office</li>
                                <li>Maintaining student records and academic documents</li>
                                <li>Handling correspondence with parents, staff, and external agencies</li>
                                <li>Assisting in the admission process and fee collection</li>
                                <li>Supporting the Principal and management team in various administrative tasks</li>
                            </ul>
                        </div>
                        <a href="#application-form" class="apply-btn">Apply Now</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="application-form" id="application-form">
            <h3>Application Form</h3>
            <p>Fill out the form below to apply for a position at Shri Siddhi Vinayak Educational Trust. We will review your application and contact you if your qualifications match our requirements.</p>
            
            <form id="careerApplicationForm" class="contact-form">
                {{ form.hidden_tag() if form }}
                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="position">Position Applied For *</label>
                        <select id="position" name="position" required>
                            <option value="">-- Select Position --</option>
                            <option value="Mathematics Teacher">Mathematics Teacher</option>
                            <option value="English Language Teacher">English Language Teacher</option>
                            <option value="Computer Science Teacher">Computer Science Teacher</option>
                            <option value="Administrative Assistant">Administrative Assistant</option>
                            <option value="Other">Other (Please specify)</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group" id="other-position-group" style="display: none;">
                    <label for="other_position">Specify Position *</label>
                    <input type="text" id="other_position" name="other_position">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="qualification">Highest Qualification *</label>
                        <input type="text" id="qualification" name="qualification" required>
                    </div>
                    <div class="form-group">
                        <label for="experience">Years of Experience *</label>
                        <input type="number" id="experience" name="experience" min="0" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="cover_letter">Cover Letter *</label>
                    <textarea id="cover_letter" name="cover_letter" rows="5" required placeholder="Briefly describe your background, skills, and why you're interested in this position"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="resume">Resume/CV (Google Drive or Dropbox Link) *</label>
                    <input type="url" id="resume" name="resume" required placeholder="https://drive.google.com/file/d/...">
                    <small>Please upload your resume to Google Drive or Dropbox and share the link above</small>
                </div>
                
                <div class="form-group">
                    <label for="references">References (Optional)</label>
                    <textarea id="references" name="references" rows="3" placeholder="Provide name, position, organization, and contact information for 2-3 professional references"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="terms" name="terms" required>
                        I hereby declare that the information provided above is true to the best of my knowledge, and I understand that providing false information may result in the disqualification of my application.
                    </label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="submit-btn">Submit Application</button>
                    <button type="reset" class="reset-btn">Reset Form</button>
                </div>
            </form>
        </div>
        
        <div class="selection-process">
            <h3>Our Selection Process</h3>
            
            <div class="process-timeline">
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h4>1. Application Screening</h4>
                        <p>All applications are reviewed by our HR team to assess qualifications, experience, and suitability for the position.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h4>2. Written Test</h4>
                        <p>Shortlisted candidates may be required to take a written test to evaluate their subject knowledge and teaching aptitude.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h4>3. Demo Class/Presentation</h4>
                        <p>Candidates who clear the written test will be invited to deliver a demonstration class or presentation to assess their teaching skills.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h4>4. Interview with Department Head</h4>
                        <p>A detailed interview with the respective department head to evaluate subject expertise and pedagogical approach.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h4>5. Final Interview with Principal/Director</h4>
                        <p>Final round of interview with the school Principal or Director to assess overall fit with the school's vision and culture.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h4>6. Offer Letter</h4>
                        <p>Successful candidates will receive an offer letter outlining the terms and conditions of employment.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="faq-section">
            <h3>Frequently Asked Questions</h3>
            
            <div class="FAQaccordion">
                <div class="FAQaccordion-item">
                    <div class="accordion-header">How can I check the status of my application?</div>
                    <div class="FAQaccordion-content">
                        <p>After submitting your application, you will receive an acknowledgment email. Shortlisted candidates will be contacted within 2-3 weeks of the application deadline. Due to the high volume of applications, we may not be able to respond to individual inquiries about application status.</p>
                    </div>
                </div>
                
                <div class="FAQaccordion-item">
                    <div class="accordion-header">Is there a probation period for new employees?</div>
                    <div class="FAQaccordion-content">
                        <p>Yes, all new employees are subject to a probation period of 3-6 months, during which their performance and suitability for the role are evaluated. Upon successful completion of the probation period, they will be confirmed as permanent employees.</p>
                    </div>
                </div>
                
                <div class="FAQaccordion-item">
                    <div class="accordion-header">What are the working hours?</div>
                    <div class="FAQaccordion-content">
                        <p>The regular school working hours are from 8:00 AM to 3:00 PM, Monday to Saturday. However, teaching staff may be required to stay beyond regular hours for staff meetings, parent-teacher meetings, or other school activities. The school remains closed on Sundays and gazetted holidays.</p>
                    </div>
                </div>
                
                <div class="FAQaccordion-item">
                    <div class="accordion-header">Do you offer any employee benefits?</div>
                    <div class="FAQaccordion-content">
                        <p>Yes, we offer various benefits to our employees, including:</p>
                        <ul>
                            <li>Provident Fund (PF) and ESI as per government norms</li>
                            <li>Medical insurance coverage</li>
                            <li>Annual performance-based increments</li>
                            <li>Fee concession for children of employees</li>
                            <li>Paid leave as per school policy</li>
                            <li>Professional development opportunities</li>
                        </ul>
                    </div>
                </div>
                
                <div class="FAQaccordion-item">
                    <div class="accordion-header">Can I apply if I don't meet all the requirements?</div>
                    <div class="FAQaccordion-content">
                        <p>We encourage you to apply if you meet most of the essential requirements and believe you have the skills and potential to excel in the role. In your cover letter, you can highlight your strengths and explain how they compensate for any missing requirements. However, please note that preference will be given to candidates who meet all the stated requirements.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="contact-section">
            <h3>Contact HR Department</h3>
            <p>If you have any questions regarding our career opportunities or the application process, please contact our HR department:</p>
            
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <p>Email: careers@ssvtrust.com</p>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-phone-alt"></i>
                    <p>Phone: +91 94133-78652</p>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <p>Office Hours: Monday to Saturday, 9:00 AM to 4:00 PM</p>
                </div>
            </div>
        </div>
    </div>
</div>
`,
        contactUs: `
<div class="mid">
    <div class="contact-container">
        <h2>Contact Us</h2>
        
        <div class="contact-info">
            <div class="contact-card">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Address</h3>
                <p>SHRI SIDDHI VINAYAK EDUCATIONAL TRUST TIBBI</p>
                <p>Village Tibbi, Hanumangarh</p>
                <p>Rajasthan - 335526</p>
            </div>
            
            <div class="contact-card">
                <i class="fas fa-phone-alt"></i>
                <h3>Phone</h3>
                <p>Main Office: +91-9876543210</p>
                <p>Admission Cell: +91-9876543211</p>
                <p>Principal: +91-9876543212</p>
            </div>
            
            <div class="contact-card">
                <i class="fas fa-envelope"></i>
                <h3>Email</h3>
                <p>info@ssveducational.org</p>
                <p>admissions@ssveducational.org</p>
                <p>principal@ssveducational.org</p>
            </div>
        </div>
        
        <div class="contact-form-container">
            <h3>Send us a Message</h3>
            <form class="contact-form">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Your Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Your Phone</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn-primary">Send Message</button>
                </div>
            </form>
        </div>
        
        <div class="map-container">
            <h3>Find Us on Map</h3>
            <div class="map-placeholder">
                <img src="{{ url_for('static', filename='images/map-placeholder.jpg') }}" alt="Map placeholder">
                <p>Interactive map will be displayed here</p>
            </div>
        </div>
    </div>
</div>
`,
admission:`
<!-- content will be fetched dynamically from admission.html -->
`,

        admission1: `
<div class="admissionmid">
            <div class="admissionmid1">
                <form action="">
                    <h2>Admission (Form-1)</h2>
            
                    <fieldset>
                        <legend>Enquiry</legend>
            
                        <!-- Left Column -->
                        <div class="form-left">
                            <label for="name">Name:</label>
                            <input type="text" name="name" id="name" placeholder="Enter Name..." required>
            
                            <label for="dob">DOB:</label>
                            <input type="date" name="dob" id="dob" required>
            
                            <label for="state">State:</label>
                            <select name="state" id="state" required>
                                <option value="">Select State</option>
                            </select>
            
                            <label for="district">District:</label>
                            <select name="district" id="district" required>
                                <option value="">Select District</option>
                            </select>
            
                            <label for="photo">Choose Photo:</label>
                            <input type="file" name="photo" id="photo" required>
                        </div>
            
                        <!-- Right Column -->
                        <div class="form-right">
                            <label for="father-name">Father's Name:</label>
                            <input type="text" name="father-name" id="father-name" placeholder="Enter Father's Name..." required>
            
                            <label for="mother-name">Mother's Name:</label>
                            <input type="text" name="mother-name" id="mother-name" placeholder="Enter Father's Name..." required>
            
                            <label>Gender:</label>
                            <div>
                                <input type="radio" name="gender" value="M" id="male" required>
                                <label for="male">Male</label>
            
                                <input type="radio" name="gender" value="F" id="female" required>
                                <label for="female">Female</label>
            
                                <input type="radio" name="gender" value="O" id="other" required>
                                <label for="other">Other</label>
                            </div>
            
                            <label>Languages You Know:</label>
                            <div>
                                <input type="checkbox" name="language[]" value="Hindi" id="hindi">
                                <label for="hindi">Hindi</label>
            
                                <input type="checkbox" name="language[]" value="English" id="english">
                                <label for="english">English</label>
            
                                <input type="checkbox" name="language[]" value="Punjabi" id="punjabi">
                                <label for="punjabi">Punjabi</label>
            
                                <input type="checkbox" name="language[]" value="Marathi" id="marathi">
                                <label for="marathi">Marathi</label>
                            </div>
                        </div>
                    </fieldset>
            
                    <fieldset>
                        <legend>Contact Details</legend>
            
                        <div class="form-left">
                        <label for="mobno">Mobile No.:</label>
                        <input type="number" name="mobno" id="mobno" required maxlength="10"  placeholder="Enter Mobile Number...">
                        </div>
                        <div class="form-right">
                        <label for="email">Email-Id:</label>
                        <input type="email" name="email" id="email" required  placeholder="Enter E-Mail Address...">
                        </div>
                    </fieldset>
            
                    <div class="form-actions">
                        <button type="submit">Submit</button>
                        <button type="reset">Reset</button>
                    </div>
                </form>
            </div>
            </div>
`,
// ********Function to Load Dynamic Content
schoolProLogin: `
 <div class="login-container">
<div class="login-box">
<h2>SchoolPro_Login</h2>
<!-- Step 1: Mobile Number Form -->
<form action="/index.html" method="get" onsubmit="validateOTP(event)">
    <label for="mobno">Mobile No : </label>
    <input type="text" id="mobno" name="mobno" minlength="10" maxlength="10" class="inputtextbox">
    <button type="button" onclick="document.getElementById('hideotp').style.display='block'">Send OTP</button>
    <section id="hideotp" style="display: none;">
        <label for="otp">Enter OTP : </label>
        <input type="password" name="otp" id="otp" class="inputtextbox">
        <button type="submit" onclick="document.getElementById('hideotp').style.display='none'">Submit</button>
        </section>
    <button type="reset">Reset</button>
</form>
</div>
</div>
`,

dummy:`
<div class="admin-dashboard">
<button onclick="adminLogout()">Logout</button>
<div class="sidebar">
<h4>Menu</h4>
<button onclick="loadAdminSection('addNotice')">Notice</button>
<button onclick="loadAdminSection('manageToppers')">Manage Toppers</button>
<button onclick="loadAdminSection('gallery')">Gallery</button>
<button onclick="loadAdminSection('faculty')">Faculty</button>
<button onclick="loadAdminSection('feeStructure')">Fee Structure</button>
</div>

<div class="main-content">
<h2>Welcome Admin</h2>
<div id="admin-section-content">
    <p>Select a section from the left to manage it.</p>
</div>
</div>

`,

// code for adminlogin starts here
adminLogin:
`<section class="adminloginsection">
<div class="login-box">
  <h2>Login</h2>
  <form>
    <div class="input-box">
      <input type="email" required>
      <label>Email</label>
    </div>
    <div class="input-box">
      <input type="password" required>
      <label>Password</label>
    </div>
    <div class="options">
      <label><input type="checkbox" checked> Remember me</label>
      <a href="#">Forgot Password?</a>
    </div>
    <button type="submit">Login</button>
    <p>Don't have an account? <a href="#"> Register here </a></p>
  </form>
</div>
</section>
`};

// code for adminlogin ends here

// code starts for noticeboard page handling*****

if (contentId === 'noticeBoard') {
    // Fetch notice data dynamically
    fetch('real/json/notices.json')
        .then(response => response.json())
        .then(notices => {
            let noticeHTML = `
<div class="mid">
    <div class="noticeboardmid">
        <div class="indexmid1">
            <div class="notice-container">
                <h2>News & Notice</h2>
                <div class="notice-container">
                    <div class="notice-scroller">
            `;

            // ✅ Filter out deleted notices
            const visibleNotices = notices.filter(notice => !notice.deleted);

            visibleNotices.forEach(notice => {
                noticeHTML += `
<div class="notice-item">
    <div class="notice-content">
        <div class="notice-date">
            <h5 class="date-number">${notice.date}</h5>
            <div class="date-divider"></div>
            <span class="date-month">${notice.month}</span>
        </div>
        <div class="notice-details">
            <p class="notice-title">${notice.title}</p>
            <p>${notice.description}</p>
            <ul class="notice-meta">
                <li><span class="meta-icon">&#128100;</span> ${notice.author}</li>
                <li><span class="meta-icon">&#128197;</span> ${notice.publish_date}</li>
            </ul>
        </div>
    </div>
</div>
                `;
            });

            noticeHTML += `
                    </div> <!-- notice-scroller -->
                </div> <!-- notice-container -->
            </div> <!-- notice-container -->
        </div> <!-- indexmid1 -->
        <div id="footer"></div> <!-- Footer will load here -->
    </div> <!-- noticeboardmid -->
</div> <!-- mid -->
            `;

            contentContainer.innerHTML = noticeHTML;
        })
        .catch(error => {
            console.error('Error loading notices:', error);
            contentContainer.innerHTML = '<h2>Error loading notices.</h2>';
        });
}

// Code for Fee Structure Page
    else if (contentId === 'feeStructure') {
        // show a quick loading message
        contentContainer.innerHTML = '<p>Loading fee structure…</p>';

        fetch('real/json/fees.json')
            .then(res => res.json())
            .then(data => {
                let html = `
<div class="mid">
  <div class="fees-section">
    <h2>Fee Structure</h2>
    
    <!-- Instructions Section -->
    <div class="container">
    <div class="instructions">
    <p><strong>Instructions:</strong></p>
    <p>1. Payment Deadlines - All fees must be paid within due date as applicable to avoid late penalties.</p>
    <p>2. Late Payment Penalty - A late fee of ₹100 per day will be applied for payments received after the due date.</p>
    <p>3. Refund Policy - Fees once paid are non-refundable, except in cases of relocation outside the city (submission of relevant proof required).</p>
    <p>4. Installment Plan - You may opt to pay tuition fees in up to three equal installments—April, August, and December—without additional interest.</p>
    <p>5. Form Submission - The fee payment receipt must be submitted to the class teacher within three days of payment.</p>
    <p>6. Fee Structure Review - The school reserves the right to revise fees annually; parents will be notified at least one month in advance of any changes.</p>
    <p>6. Contact for Queries - For any fee-related queries please contact the Accounts Office.</p>
    </div>
    </div>
       
    <p class="fees-subtitle">${data.subtitle}</p>
    <div class="table-responsive">
      <table>
        <thead><tr>`;

                data.columns.forEach(col => {
                    html += `<th>${col}</th>`;
                });
                html += `</tr></thead><tbody>`;

                data.rows.forEach(row => {
                    html += `<tr>`;
                    row.forEach(cell => {
                        html += (typeof cell === 'number')
                            ? `<td>₹${cell.toLocaleString()}</td>`
                            : `<td>${cell}</td>`;
                    });
                    html += `</tr>`;
                });

                html += `</tbody></table>
    </div>
  </div>
</div>`;

                contentContainer.innerHTML = html;
            })
            .catch(err => {
                console.error('Error loading fees:', err);
                contentContainer.innerHTML = '<h2>Error loading fee structure.</h2>';
            });
    }
// dynamic timetable html page starts

else if (contentId === 'timeTable') {
    // Show loading message
    contentContainer.innerHTML = '<p>Loading timetable...</p>';

    fetch('real/templates/academics/time_table.html')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            contentContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading time_table.html:', error);
            contentContainer.innerHTML = '<h2>Error loading timetable.</h2>';
        });
}

// dynamic timetable html page ends


// dynamic syllabus html page starts

else if (contentId === 'syllabus') {
    contentContainer.innerHTML = '<p>Loading syllabus...</p>';

    fetch('real/templates/academics/syllabus.html')
        .then(response => {
            if (!response.ok) throw new Error('Failed to load syllabus.');
            return response.text();
        })
        .then(html => {
            contentContainer.innerHTML = html;
        })
        .catch(err => {
            contentContainer.innerHTML = '<h2>Error loading syllabus.</h2>';
            console.error(err);
        });
}

// dynamic syllabus html page ends


// dynamic photovideo html page starts
else if (contentId === 'photos') {
  fetch('real/templates/gallery/photos.html')
    .then(res => res.text())
    .then(html => {
      contentContainer.innerHTML = html;
      setTimeout(initPhotoGallery, 0);
    });
}

else if (contentId === 'videos') {
  fetch('real/templates/gallery/videos.html')
    .then(res => res.text())
    .then(html => {
      contentContainer.innerHTML = html;
      setTimeout(initVideoGallery, 0);
    });
}

// dynamic photovideo html page ends

// dynamic admission html page starts

else if (contentId === 'admission') {
  contentContainer.innerHTML = '<p>Loading admission page...</p>';

  fetch('real/templates/join/admission.html')
    .then(response => {
      if (!response.ok) throw new Error('Network response was not ok');
      return response.text();
    })
    .then(html => {
      contentContainer.innerHTML = html;

      // Dynamically load admission.js
      const script = document.createElement('script');
      script.src = 'real/js/admission.js';
      document.body.appendChild(script);
    })
    .catch(error => {
      console.error('Error loading admission.html:', error);
      contentContainer.innerHTML = '<h2>Error loading admission.html</h2>';
    });
}

// dynamic admission html page ends

// dynamic check application status page starts

else if (contentId === 'check_application') {
  contentContainer.innerHTML = '<p>Loading application status checker...</p>';

  fetch('real/templates/join/check_application.html')
    .then(response => {
      if (!response.ok) throw new Error('Network response was not ok');
      return response.text();
    })
    .then(html => {
      // Create a temporary container to parse the HTML
      const temp = document.createElement('div');
      temp.innerHTML = html;
      
      // Extract script tags
      const scripts = temp.querySelectorAll('script');
      let scriptContent = '';
      
      scripts.forEach(script => {
        scriptContent += script.textContent + '\n';
      });
      
      // Remove script tags from HTML before inserting
      scripts.forEach(script => script.remove());
      
      // Insert the HTML content
      contentContainer.innerHTML = temp.innerHTML;
      
      // Execute the extracted scripts
      if (scriptContent) {
        try {
          eval(scriptContent);
          console.log('[check_application] Scripts executed successfully');
        } catch(err) {
          console.error('[check_application] Error executing scripts:', err);
        }
      }
      
      console.log('[check_application] page loaded');
    })
    .catch(error => {
      console.error('Error loading check_application.html:', error);
      contentContainer.innerHTML = '<h2>Error loading application status page</h2>';
    });
}

// dynamic check application status page ends

// dynamic careers html page starts
// script.js

else if (contentId === 'careers') {
    contentContainer.innerHTML = '<p>Loading careers...</p>';

    // path changed from academics folder to join folder where file actually resides
    fetch('real/templates/join/careers.html?v=2')
        .then(response => {
            if (!response.ok) throw new Error('Failed to load careers.');
            return response.text();
        })
        .then(html => {
            contentContainer.innerHTML = html;

            // ✅ Run this after content is injected
            if (typeof initializeCareersJS === 'function') {
                initializeCareersJS();
            } else {
                console.warn('initializeCareersJS function not found.');
            }
        })
        .catch(err => {
            contentContainer.innerHTML = '<h2>Error loading careers.</h2>';
            console.error(err);
        });
}

// dynamic careers html page ends

else if (contentData[contentId]) {
    contentContainer.innerHTML = contentData[contentId];

// Call data loading function for faculties or toppers
    if (contentId === 'faculties') {
        loadFacultyData();
    } else if (contentId === 'toppers') {
        loadToppersData();
    } else if (contentId === 'financials') {
        loadFinancialsData();
    }

} else {
    contentContainer.innerHTML = '<h2>Content not found</h2>';
}
// *********Index.html Page content ends here *********************


//************************************** * Loading Header Footer

document.addEventListener("DOMContentLoaded", function () {
    // Load header
    fetch("header.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("header").innerHTML = data;
        });

    // Load footer
    fetch("footer.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("footer").innerHTML = data;
        });
});


// TOPPERS AND FACULTY DATA
document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM fully loaded");
});

// ****** ✅ Load Faculty Data ONLY when "Faculties" menu is clicked
async function loadFacultyData() {
    const contentContainer = document.getElementById("content-container");

    // Clear previous content before loading new data
    contentContainer.innerHTML = `<h2>Our Faculties</h2><div id="facultyContainer"></div>`;

    try {
        // ✅ CACHE-BUSTING: Append timestamp to prevent caching old faculty data
        const response = await fetch("real/json/facultyData.json?" + new Date().getTime());
        if (!response.ok) throw new Error("Failed to fetch faculty data.");

        const facultyData = await response.json();
        const container = document.getElementById("facultyContainer");

        facultyData.forEach((faculty) => {
            const card = document.createElement("div");
            card.className = "faculty-card";
            card.innerHTML = `
                <img src="${faculty.image}" alt="Faculty Image">
                <h3>${faculty.name}</h3>
                <p>${faculty.title}</p>
            `;
            container.appendChild(card);
        });

        console.log("✅ Faculty data loaded successfully.");
    } catch (error) {
        console.error("Error loading faculty data:", error);
    }
}

// ************************************** 
//// ✅ Load Toppers Data ONLY when "Toppers" menu is clicked
async function loadToppersData() {
    const contentContainer = document.getElementById("content-container");
    const sessionsContainer = document.getElementById("sessionsContainer");
    const toppersContainer = document.getElementById("toppersContainer");

    // Clear previous content before loading new data
    sessionsContainer.innerHTML = '';
    toppersContainer.innerHTML = '';

    try {
        // ✅ CACHE-BUSTING: Append timestamp to prevent caching old toppers data
        const response = await fetch("real/json/toppersData.json?" + new Date().getTime());
        if (!response.ok) throw new Error("Failed to fetch toppers data.");

        const toppersData = await response.json();

        // Dynamically create session buttons
        for (const year in toppersData) {
            if (!toppersData.hasOwnProperty(year)) continue;

            const button = document.createElement('button');
            button.textContent = `Session ${year}`;
            button.className = 'session-button';
            button.addEventListener('click', () => showToppersForSession(year, toppersData[year]));

            sessionsContainer.appendChild(button);
        }

        console.log("✅ Toppers data loaded successfully.");
    } catch (error) {
        console.error("Error loading toppers data:", error);
    }
}

// Function to show toppers for the selected session
function showToppersForSession(year, toppers) {
    const toppersContainer = document.getElementById("toppersContainer");

    // Clear previous toppers
    toppersContainer.innerHTML = '';

    const sessionHeader = document.createElement('h2');
    sessionHeader.textContent = `Toppers of ${year}`;
    toppersContainer.appendChild(sessionHeader);

    // Display toppers for the selected session
    toppers.forEach(topper => {
        const card = document.createElement("div");
        card.className = "toppers-card";
        card.innerHTML = `
            <img src="${topper.image}" 
                 onerror="this.src='images/default_profile.png'" 
                 alt="${topper.name}"
                 class="toppers-image">
            <h4>${topper.name}</h4>
            <p>Class: ${topper.class}</p>
            <p>Rank: ${topper.rank}</p>
        `;
        toppersContainer.appendChild(card);
    });
}

// Load Financial Documents Data
async function loadFinancialsData() {
    const financialsContainer = document.getElementById('financialsContainer');
    
    if (!financialsContainer) return;

    try {
        const response = await fetch('real/json/financials.json');
        if (!response.ok) throw new Error('Failed to fetch financials data');
        
        const financials = await response.json();
        
        // Clear loading message
        financialsContainer.innerHTML = '';
        
        if (!financials || financials.length === 0) {
            financialsContainer.innerHTML = '<p style="text-align: center; color: #999;">No financial documents available.</p>';
            return;
        }
        
        // Group documents by category
        const grouped = {};
        financials.forEach(doc => {
            if (doc.visibility !== 'public') return; // Only show public documents
            
            const category = doc.category || 'Other';
            if (!grouped[category]) {
                grouped[category] = [];
            }
            grouped[category].push(doc);
        });
        
        // Create HTML for each category
        let html = '';
        const categories = Object.keys(grouped).sort();
        
        categories.forEach((category, index) => {
            html += `
                <details class="fy-group" ${index === 0 ? 'open' : ''}>
                    <summary class="fy-title">${category}</summary>
                    <div class="financials-grid">
            `;
            
            grouped[category].forEach(doc => {
                const fileName = doc.document_url.split('/').pop();
                const fileIcon = getFileIcon(doc.document_url);
                
                html += `
                    <div class="financial-card">
                        <div class="financial-type">${doc.category}</div>
                        <div class="financial-year">${doc.date_published || 'N/A'}</div>
                        <a href="${doc.document_url}" target="_blank" class="pdf-link" title="${doc.title}">
                            ${fileIcon}
                        </a>
                        <div style="text-align: center; font-size: 12px; margin-top: 8px; color: #666;">
                            ${doc.title}
                        </div>
                    </div>
                `;
            });
            
            html += `
                    </div>
                </details>
            `;
        });
        
        financialsContainer.innerHTML = html;
        
    } catch (error) {
        console.error('Error loading financials:', error);
        financialsContainer.innerHTML = '<p style="text-align: center; color: #d32f2f;">Error loading financial documents. Please try again later.</p>';
    }
}

// Get appropriate file icon based on file type
function getFileIcon(filePath) {
    const ext = filePath.split('.').pop().toLowerCase();
    
    const icons = {
        'pdf': '<i class="fas fa-file-pdf" style="font-size: 32px; color: #d32f2f;"></i>',
        'doc': '<i class="fas fa-file-word" style="font-size: 32px; color: #2196f3;"></i>',
        'docx': '<i class="fas fa-file-word" style="font-size: 32px; color: #2196f3;"></i>',
        'xls': '<i class="fas fa-file-excel" style="font-size: 32px; color: #4caf50;"></i>',
        'xlsx': '<i class="fas fa-file-excel" style="font-size: 32px; color: #4caf50;"></i>',
        'ppt': '<i class="fas fa-file-powerpoint" style="font-size: 32px; color: #ff6f00;"></i>',
        'pptx': '<i class="fas fa-file-powerpoint" style="font-size: 32px; color: #ff6f00;"></i>',
        'txt': '<i class="fas fa-file-text" style="font-size: 32px; color: #999;"></i>',
        'jpg': '<i class="fas fa-file-image" style="font-size: 32px; color: #9c27b0;"></i>',
        'jpeg': '<i class="fas fa-file-image" style="font-size: 32px; color: #9c27b0;"></i>',
        'png': '<i class="fas fa-file-image" style="font-size: 32px; color: #9c27b0;"></i>',
        'gif': '<i class="fas fa-file-image" style="font-size: 32px; color: #9c27b0;"></i>'
    };
    
    return icons[ext] || '<i class="fas fa-file" style="font-size: 32px; color: #999;"></i>';
}



//************************************** * Script for Photo Gallery Page Handling
//************************************** * JavaScript for handling both Image and Video Gallery
let currentIndex = 0;
let mediaType = ''; // 'image' or 'video'

const images = [
    'images/Event1.jpg', 'images/Event2.jpg', 'images/Event3.jpg', 'images/Event4.jpg',
    'images/Event5.jpg', 'images/Event6.jpg', 'images/Event7.jpg', 'images/Event8.jpg',
    'images/Event9.jpg', 'images/Event10.jpg'
];

const videos = [
    'c5I199uvV2E', 'bdbEGwtyxWA', '8AW2zrBYjBw', 'xRdStHvg5-o', '0smgsj8wGkQ',
    'oGYtYt8BKWg', '5meDiZ7PfhY', 'ol6cd37mKxk', 'E_IwgcF34Ik', 'yTFnkTTWHT4'
];

// Function to open the lightbox
function openLightbox(index, type) {
    currentIndex = index;
    mediaType = type;
    const lightboxContent = document.getElementById('lightbox-content');
    const lightbox = document.getElementById('lightbox');

    if (mediaType === 'image') {
        lightboxContent.innerHTML = `<img src="${images[currentIndex]}" alt="Image" style="width:100%;">`;
    } else {
        lightboxContent.innerHTML = `<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/${videos[currentIndex]}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`;
    }

    lightbox.style.display = 'flex';
}

// Function to close the lightbox
function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
}

// Function to change media (image/video)
function changeMedia(direction) {
    if (mediaType === 'image') {
        currentIndex = (currentIndex + direction + images.length) % images.length;
        openLightbox(currentIndex, 'image');
    } else {
        currentIndex = (currentIndex + direction + videos.length) % videos.length;
        openLightbox(currentIndex, 'video');
    }
}

//***************************** NoticeBoard Page

// Function to Display Noticeboard
function displayNotices() {
    const noticeList = document.getElementById("notice-list");
    noticeList.innerHTML = ""; // Clear existing notices

    const categoryFilter = document.getElementById("category-filter").value;
    const searchQuery = document.getElementById("search-input").value.toLowerCase();

    // Filter notices based on category and search input
    const filteredNotices = notices.filter(notice => {
        const matchesCategory = categoryFilter === "all" || notice.category === categoryFilter;
        const matchesSearch = notice.title.toLowerCase().includes(searchQuery) || notice.description.toLowerCase().includes(searchQuery);
        return matchesCategory && matchesSearch;
    });

    // Loop through filtered notices and display them
    filteredNotices.forEach(notice => {
        const noticeItem = document.createElement("div");
        noticeItem.classList.add("notice-item");

        noticeItem.innerHTML = `
<h3>${notice.title}</h3>
<p>${notice.description}</p>
<span>Posted on: ${new Date(notice.date).toLocaleDateString()}</span>
`;

        noticeList.appendChild(noticeItem);
    });
}

// Event listeners for the filters
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("category-filter")?.addEventListener("change", displayNotices);
    document.getElementById("search-input")?.addEventListener("input", displayNotices);
});


// Initial loading of notices
window.onload = displayNotices;




// ************************************** *RTE PAGE
// ************************************** *RTE Application Process

// Inject the HTML into the page dynamically
document.getElementById('applicationSection').innerHTML = applicationProcess;

// Add event listener for the "Click here" link
document.getElementById('viewPdf').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent default link behavior

    const pdfUrl = "/Documents/rte_time_frame.pdf"; // Path to your PDF
    const pdfViewer = document.getElementById('pdfViewer');
    const pdfContainer = document.getElementById('pdfContainer');

    pdfViewer.src = pdfUrl; // Set the iframe source
    pdfContainer.classList.remove('hidden'); // Remove the hidden class to show the container
});


// // ***********************************************Admission Page
// // Complete list of states and their districts
// // Define states and their respective districts
// // State and district data
// const stateDistricts = {
//     "Andhra Pradesh": ["Anantapur", "Chittoor", "East Godavari"],
//     "Delhi": ["Central Delhi", "New Delhi", "North Delhi"],
//     "Goa": ["North Goa", "South Goa"],
//     "Rajasthan": ["Ajmer", "Alwar", "Barmer", "Jaipur"],
//     "Uttar Pradesh": ["Agra", "Aligarh", "Lucknow", "Varanasi"],
// };

// // DOM elements
// const stateSelect = document.getElementById("state");
// const districtSelect = document.getElementById("district");

// // Function to populate state dropdown
// function populateStates() {
//     for (let state in stateDistricts) {
//         const option = document.createElement("option");
//         option.value = state;
//         option.textContent = state;
//         stateSelect.appendChild(option);
//     }
// }

// // Function to populate district dropdown based on state
// function populateDistricts() {
//     const selectedState = stateSelect.value;
//     districtSelect.innerHTML = '<option value="">Select District</option>'; // Reset districts

//     if (selectedState && stateDistricts[selectedState]) {
//         stateDistricts[selectedState].forEach((district) => {
//             const option = document.createElement("option");
//             option.value = district;
//             option.textContent = district;
//             districtSelect.appendChild(option);
//         });
//     }
// }

// // Add event listeners
// document.addEventListener("DOMContentLoaded", populateStates); // Load states on page load
// stateSelect.addEventListener("change", populateDistricts);


// School Pro login page;
document.addEventListener("DOMContentLoaded", function () {
    let generatedOTP = null; // Declare globally for proper scope handling

    document.getElementById("sendOtpBtn").addEventListener("click", sendOTP);
    document.getElementById("loginForm").addEventListener("submit", validateOTP);

    async function sendOTP() {
        const mobileNumber = document.getElementById('mobno').value.trim();

        if (mobileNumber.length !== 10 || isNaN(mobileNumber)) {
            alert("Please enter a valid 10-digit mobile number.");
            return;
        }

        // Generate 6-digit OTP
        generatedOTP = Math.floor(100000 + Math.random() * 900000);
        console.log("Generated OTP:", generatedOTP);
        alert("Your OTP is: " + generatedOTP);

        document.getElementById('hideotp').style.display = 'block'; // Show OTP section

        // SMS API details
        const baseUrl = "https://alerts.prioritysms.com/api/v4/";
        const apiKey = "A085b04763d2e186ca1f640d414241485";
        const senderID = "SIDHIS";
        const message = encodeURIComponent(`Dear user, Your OTP to login SchoolPro is ${generatedOTP}. Shri Siddhi Vinayak Shikshan Sansthan`);
        const apiUrl = `${baseUrl}?api_key=${apiKey}&method=sms&message=${message}&to=91${mobileNumber}&sender=${senderID}`;

        try {
            const response = await fetch(apiUrl, { method: "POST" });
            const textResponse = await response.text();
            console.log("API Response:", textResponse);

            if (textResponse.toLowerCase().includes("success")) {
                alert("OTP sent successfully!");
            } else {
                alert("Failed to send OTP. Response: " + textResponse);
            }
        } catch (error) {
            console.error("Error sending OTP:", error);
            alert("Error sending OTP: " + error.message);
        }
    }

    function validateOTP(event) {
        const enteredOTP = document.getElementById('otp').value;

        if (!generatedOTP) {
            alert("Please request an OTP first.");
            event.preventDefault();
            return;
        }

        if (enteredOTP != generatedOTP) {
            alert("Invalid OTP! Please try again.");
            event.preventDefault();
        }
    }
});
}


//******* */ Admin Login Handler
document.addEventListener('submit', function(event) {
    if (event.target.closest('.adminloginsection form')) {
        event.preventDefault(); // Prevent default form submit

        const email = event.target.querySelector('input[type="email"]').value;
        const password = event.target.querySelector('input[type="password"]').value;

        // Dummy check – Replace with server-side check via fetch() or AJAX
        if (email === "aks01240@gmail.com" && password === "1") {
            loadAdminDashboard(); // Function you define to load dashboard
        } else {
            alert("Invalid credentials. Please try again.");
        }
    }
});

// Loading Admin Dashboard
function loadAdminDashboard() {
    const contentContainer = document.getElementById('content-container');
    contentContainer.innerHTML = `
        <div class="admin-dashboard">

        <div class="sidebar">
        <h4>Menu</h4>
        <button onclick="adminLogout()">Logout</button>
        <button onclick="loadAdminSection('addNotice')">Manage Notices</button>
        <button onclick="loadAdminSection('manageToppers')">Manage Toppers</button>
        <button onclick="loadAdminSection('gallery')">Manage Gallery</button>
        <button onclick="loadAdminSection('faculty')">Manage Faculty</button>
        <button onclick="loadAdminSection('feeStructure')">Manage Fee Structure</button>
        <button onclick="loadAdminSection('feeStructure')">Manage Financials</button>
        <button onclick="loadAdminSection('careerApplications')">Career Applications</button>
    </div>

    <div class="main-content">
        <h2>Welcome Admin</h2>
        <div id="admin-section-content">
            <p>Select a section from the left to manage it.</p>
        </div>
    </div>

    `;
}


// After Login - Admin Dashboard
function loadAdminSection(sectionId) {
    const adminSection = document.getElementById('admin-section-content');

    if (sectionId === 'addNotice') {
        adminSection.innerHTML = `
            <div id="admin-section-content">
                <h3>Add New Notice</h3>
                <form id="noticeForm">
                    <input type="text" id="title" placeholder="Title" required>
                    <textarea id="description" placeholder="Description" required></textarea>
                    <input type="text" id="author" placeholder="Author" required>
                    <button type="submit">Submit</button>
                </form>
                <div id="notice-status"></div>
            </div>
             <hr>
            <h3>Existing Notices</h3>
            <div id="notices-list"></div>
        `;
     // FORM SUBMIT HANDLER
        document.getElementById('noticeForm').addEventListener('submit', function (e) {
            e.preventDefault();
    
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;
            const author = document.getElementById('author').value;
    
            const today = new Date();
            const date = String(today.getDate()).padStart(2, '0');
            const month = today.toLocaleString('default', { month: 'short' });
            const publish_date = `${date}-${month}-${today.getFullYear()}`;
    
            const noticeData = {
                date,
                month,
                title,
                description,
                author,
                publish_date
            };
    
            fetch('real/php/notice.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(noticeData)
            })
            .then(response => response.json())
            .then(data => {
            const statusElement = document.getElementById('notice-status');
            statusElement.innerText = data.message;

            setTimeout(() => {
                statusElement.innerText = '';
            }, 10000); // Clears after 10 seconds

                loadNotices(); // ✅ LOAD NEW NOTICES AFTER ADDITION
            })
            .catch(err => {
                document.getElementById('notice-status').innerText = "Failed to add notice.";
                console.error(err);
            });
        });
        // ✅ LOAD NOTICES INITIALLY WHEN SECTION LOADS
        loadNotices();
    }
    
else if (sectionId === 'careerApplications') {
    // load the career admin page via AJAX and inject it
    fetch('real/php/admin_careers.php')
        .then(r => r.text())
        .then(html => { adminSection.innerHTML = html; })
        .catch(err => { adminSection.innerHTML = '<p>Error loading Career Applications.</p>'; console.error(err); });
}
    
else if (sectionId === 'manageToppers') {
    adminSection.innerHTML = `
    <h2>Manage Toppers</h2>
    <form id="toppersForm" enctype="multipart/form-data">
        <label for="year">Year:</label>
        <select id="toppersYear" name="year" required>
            <option value="">Select Year</option>
            <option value="2024">2024</option>
            <option value="2023">2023</option>
            <option value="2022">2022</option>
            <option value="2021">2021</option>
            <option value="2020">2020</option>
        </select><br><br>

        <label for="name">Name:</label>
        <input type="text" id="topperName" name="name" placeholder="Topper Name" required><br><br>

        <label for="class">Class:</label>
        <input type="text" id="topperClass" name="class" placeholder="Class" required><br><br>

        <label for="rank">Rank/Percentage:</label>
        <input type="text" id="topperRank" name="rank" placeholder="Rank or %" required><br><br>

        <label for="image">Image:</label>
        <input type="file" id="topperImage" name="image" accept="image/*"><br><br>

        <button type="submit">Add Topper</button>
    </form>
    <div id="toppers-status"></div>
    <hr>
    <h3>Topper Entries for Selected Year</h3>
    <div id="toppersList"></div>
    `;

    let toppersData = {};
    const form = document.getElementById('toppersForm');
    const statusDiv = document.getElementById('toppers-status');
    const listDiv = document.getElementById('toppersList');
    const yearSelect = document.getElementById('toppersYear');

    function showStatus(msg, color = 'green') {
        statusDiv.innerText = msg;
        statusDiv.style.color = color;
        setTimeout(() => statusDiv.innerText = '', 8000);
    }

    function loadToppers(yearSelected = null) {
        fetch('real/json/toppersData.json')
            .then(res => res.json())
            .then(data => {
                toppersData = data;
                displayToppers(yearSelected || yearSelect.value);
            })
            .catch(() => showStatus("Error loading toppers.", "red"));
    }

    function saveToppers() {
        fetch('real/php/save_toppers.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(toppersData)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showStatus('Saved successfully!');
                displayToppers(yearSelect.value);
            } else {
                showStatus('Save failed.', 'red');
            }
        })
        .catch(() => showStatus('Save error.', 'red'));
    }

    function displayToppers(year) {
        listDiv.innerHTML = '';
        if (!year || !toppersData[year]) {
            listDiv.innerText = "No data for selected year.";
            return;
        }

        toppersData[year].forEach((topper, index) => {
            const div = document.createElement('div');
            div.style.borderBottom = '1px solid #ccc';
            div.style.padding = '10px 0';
            div.style.opacity = topper.deleted ? 0.5 : 1;

            div.innerHTML = `
                <strong>${topper.name}</strong><br>
                Class: ${topper.class}<br>
                Rank: ${topper.rank}<br>
                <img src="${topper.image}" width="100"><br>
                <button class="toggle-btn" data-index="${index}">${topper.deleted ? 'Restore' : 'Delete'}</button>
            `;

            listDiv.appendChild(div);
        });
    }

    listDiv.addEventListener('click', function (e) {
        if (e.target.classList.contains('toggle-btn')) {
            const index = e.target.dataset.index;
            const year = yearSelect.value;
            toppersData[year][index].deleted = !toppersData[year][index].deleted;
            saveToppers();
        }
    });

    yearSelect.addEventListener('change', () => {
        displayToppers(yearSelect.value);
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const year = formData.get('year');
        const name = formData.get('name');
        const className = formData.get('class');
        const rank = formData.get('rank');
        const imageFile = formData.get('image');

        if (!year || !name || !className || !rank) {
            showStatus("All fields except image are required.", "red");
            return;
        }

        function addNewTopper(imagePath) {
            const newTopper = {
                name,
                class: className,
                rank,
                image: imagePath || 'real/images/Toppers/default.jpg',
                deleted: false
            };
            if (!toppersData[year]) toppersData[year] = [];
            toppersData[year].push(newTopper);
            saveToppers();
            form.reset();
        }

        if (imageFile && imageFile.name) {
            const imgUploadData = new FormData();
            imgUploadData.append('topperImage', imageFile);

            fetch('real/php/upload_topper_image.php', {
                method: 'POST',
                body: imgUploadData
            })
            .then(res => res.json())
            .then(imgRes => {
                if (imgRes.success) {
                    addNewTopper(imgRes.imagePath);
                } else {
                    showStatus("Image upload failed: " + imgRes.message, "red");
                }
            })
            .catch(() => showStatus("Image upload error.", "red"));
        } else {
            addNewTopper(); // no image selected
        }
    });

    // load on render
    loadToppers();
}



//     else if (sectionId === 'manageToppers') {
//     adminSection.innerHTML = `
//     <h2>Manage Toppers</h2>
//     <form id="toppersForm" enctype="multipart/form-data">
//         <label for="year">Year:</label>
//         <select id="toppersYear" name="year" required>
//             <option value="">Select Year</option>
//             <option value="2024">2024</option>
//             <option value="2023">2023</option>
//             <option value="2022">2022</option>
//             <option value="2021">2021</option>
//             <option value="2020">2020</option>
//         </select><br><br>

//         <label for="name">Name:</label>
//         <input type="text" id="topperName" name="name" placeholder="Topper Name" required><br><br>

//         <label for="class">Class:</label>
//         <input type="text" id="topperClass" name="class" placeholder="Class" required><br><br>

//         <label for="rank">Rank/Percentage:</label>
//         <input type="text" id="topperRank" name="rank" placeholder="Rank or %" required><br><br>

//         <label for="image">Image:</label>
//         <input type="file" id="topperImage" name="image" accept="image/*" required><br><br>

//         <button type="submit">Add Topper</button>
//     </form>
//     <div id="toppers-status"></div>
//     <hr>
//     <h3>Topper Entries for Selected Year</h3>
//     <div id="toppersList"></div>
//     `;

//     loadToppers();

//     document.getElementById('toppersForm').addEventListener('submit', function (e) {
//         e.preventDefault();

//         const form = document.getElementById('toppersForm');
//         const formData = new FormData(form);

//         const year = formData.get('year');
//         const name = formData.get('name');
//         const className = formData.get('class');
//         const rank = formData.get('rank');
//         const image = formData.get('image');

//         if (!year || !name || !className || !rank || !image) {
//             document.getElementById('toppers-status').innerText = "All fields are required.";
//             return;
//         }

//         fetch('real/php/save_toppers.php', {
//             method: 'POST',
//             body: formData
//         })
//         .then(response => response.json())
//         .then(data => {
//             document.getElementById('toppers-status').innerText = data.message;
//             if (data.success) {
//                 loadToppers(year);
//                 form.reset();
//             }
//             setTimeout(() => {
//                 document.getElementById('toppers-status').innerText = "";
//             }, 10000);
//         })
//         .catch(error => {
//             document.getElementById('toppers-status').innerText = "Error saving topper.";
//             console.error("Error:", error);
//         });
//     });

// function loadToppers(yearSelected = null) {
//     fetch('real/json/toppersData.json')
//         .then(res => res.json())
//         .then(data => {
//             const year = yearSelected || document.getElementById('toppersYear').value;
//             const listDiv = document.getElementById('toppersList');
//             listDiv.innerHTML = '';

//             if (year && data[year]) {
//                 data[year].forEach((topper, index) => {
//                     listDiv.innerHTML += `
//                         <div style="margin-bottom: 10px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
//                             <strong>${topper.name}</strong><br>
//                             Class: ${topper.class}<br>
//                             Rank: ${topper.rank}<br>
//                             <img src="${topper.image}" alt="${topper.name}" width="100">
//                         </div>`;
//                 });
//             } else {
//                 listDiv.innerHTML = "No data found for selected year.";
//             }
//         });
// }}


    else if (sectionId === 'gallery') {
        adminSection.innerHTML = `
            <h3>Gallery</h3>
            <p>Image upload and gallery tools will go here...</p>
        `;
    }

    else if (sectionId === 'faculty') {
        adminSection.innerHTML = `
            <h3>Faculty</h3>
            <p>Faculty management section under development.</p>
        `;
    }

    else if (sectionId === 'feeStructure') {
        adminSection.innerHTML = `
            
            <h3>Update Fee Structure</h3>
            <div id="feeTableContainer"></div>
            <button id="saveFeesBtn">Save Your Changes</button>
            <div id="feeUpdateStatus"></div>
        `;
        loadFeeStructureEditor();  // Load JSON and build editable table
    }
}


// Hamburger menu functionality
const hamburger = document.getElementById('hamburger-menu');
const navigation = document.querySelector('.navigation');

if (hamburger && navigation) {
    hamburger.addEventListener('click', () => {
        navigation.classList.toggle('active');
        hamburger.classList.toggle('active');
    });
}

    // Marquee for notices
    const noticesMarquee = document.getElementById('noticesMarquee');
    if (noticesMarquee) {
        fetch('real/json/notices.json')
            .then(response => response.json())
            .then(notices => {
                if (notices && notices.length > 0) {
                    let marqueeContent = '';
                    notices.forEach(notice => {
                        if(!notice.deleted){
                        marqueeContent += `<span class="red">NEW</span> ${notice.title} | `;
                        }
                    });
                    noticesMarquee.innerHTML = marqueeContent;
                } else {
                    noticesMarquee.innerHTML = "Welcome to SHRI SIDDHI VINAYAK EDUCATIONAL TRUST TIBBI";
                }
            })
            .catch(error => {
                console.error('Error loading notices for marquee:', error);
                noticesMarquee.innerHTML = "Welcome to SHRI SIDDHI VINAYAK EDUCATIONAL TRUST TIBBI";
            });
    }

// // Function to load notices under marquee tag dynamically
// function loadNotices() {
//     fetch('real/json/notices.json')
//         .then(response => response.json())
//         .then(data => {
//             const notices = data;
//             const marqueeElement = document.getElementById('noticesMarquee');

//             // Clear any existing content
//             marqueeElement.innerHTML = '';

//             // Loop through each notice and append it to the marquee
//             notices.forEach(notice => {
//                 const noticeElement = document.createElement('span');
//                 // Create a formatted notice with date and title
//                 noticeElement.innerHTML = `<img src="images/new.gif" alt="New Event">${notice.publish_date} - ${notice.title}`;
//                 marqueeElement.appendChild(noticeElement);
//             });
//         })
//         .catch(error => console.error('Error loading notices:', error));
// }

// Call the function to load notices when the page loads
window.onload = loadNotices;
// Get the menu toggle button and navbar
const menuToggle = document.getElementById('menu-toggle');
const navbar = document.querySelector('.navbar .navigation');

if (menuToggle && navbar) {
    menuToggle.addEventListener('click', () => {
        navbar.classList.toggle('active');
    });
}

function loadNotices() {
    fetch('real/json/notices.json')
        .then(response => response.json())
        .then(notices => {
            const container = document.getElementById('notices-list');
            if (!container) return;
            container.innerHTML = '';

            notices.forEach((notice, index) => {
                if (notice.deleted) return; // skip soft-deleted

                const div = document.createElement('div');
                div.classList.add('notice-card');
                div.innerHTML = `
                    <h4>${notice.title}</h4>
                    <small>${notice.publish_date} — ${notice.author}</small>
                    <p>${notice.description}</p>
                    <button onclick="deleteNotice('${notice.notice_id}')">Delete</button>
                `;
                container.appendChild(div);
            });
        });
}

function deleteNotice(noticeId) {
    fetch('real/php/notice.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'delete', notice_id: noticeId })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        loadNotices(); // refresh list
    });
}

// manageToppers
  let toppersData = {};

  // Load existing data
  fetch('real/json/toppersData.json')
    .then(res => res.json())
    .then(data => {
      toppersData = data;
      displayToppers();
    })
    .catch(err => console.error("Failed to load toppers data", err));

  function displayToppers() {
    const yearElement = document.getElementById("toppersYear");
    const list = document.getElementById("toppersList");
    if (!yearElement || !list) return;
    const year = yearElement.value;
    list.innerHTML = "";

    if (year && toppersData[year]) {
      toppersData[year].forEach((entry, index) => {
        const div = document.createElement("div");
        div.className = "topper-item";
        div.innerHTML = `
        <div class="topper-card">
        <div class="topper-info">
            <img src="${entry.image}" alt="${entry.name}">
            <div class="topper-text">
            <strong>${entry.name}</strong><br>
            <span>${entry.class}</span> - <span>${entry.rank}</span>
            </div>
        </div>
        <button class="delete-btn" onclick="deleteTopper('${year}', ${index})">Delete</button>
        </div>
        `;
        list.appendChild(div);
      });
    }
  }

  const toppersYearControl = document.getElementById("toppersYear");
  if (toppersYearControl) {
    toppersYearControl.addEventListener("change", displayToppers);
  }

  const toppersForm = document.getElementById("toppersForm");
  if (toppersForm) {
    toppersForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const yearElement = document.getElementById("toppersYear");
      const nameElement = document.getElementById("topperName");
      const classElement = document.getElementById("topperClass");
      const rankElement = document.getElementById("topperRank");
      const imageElement = document.getElementById("topperImage");
      const statusElement = document.getElementById('toppers-status');

      if (!yearElement || !nameElement || !classElement || !rankElement || !imageElement) return;

      const year = yearElement.value;
      const name = nameElement.value;
      const cls = classElement.value;
      const rank = rankElement.value;
      const image = imageElement.value;

      if (!toppersData[year]) toppersData[year] = [];
      toppersData[year].push({ name, class: cls, rank, image });
      displayToppers();
      
      if (statusElement) {
        statusElement.innerText = "Topper added successfully.";
        setTimeout(() => {
          statusElement.innerText = '';
        }, 10000);
      }

      this.reset();
    });
  }

  function deleteTopper(year, index) {
    if (confirm("Are you sure to delete this topper?")) {
      toppersData[year].splice(index, 1);
      displayToppers();
      const statusElement = document.getElementById('toppers-status');
      if (statusElement) {
        statusElement.innerText = "Topper deleted.";
        setTimeout(() => {
          statusElement.innerText = '';
        }, 10000);
      }
    }
  }

  