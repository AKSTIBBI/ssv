// *********Index.html Page content starts here *********************
function loadContent(contentId) {
    var contentContainer = document.getElementById('content-container');
// All the static HTML snippets for each tab
const contentData = {
    home: `
  <h2>Welcome to the Home Page</h2>
  <p>This is the homepage of the Trust website.</p>
  `,
    schoolProfile: `
  <div class="mid">
      <div class="Profilemid">
          <div class="profilemid1">
              <h2>Trust at a Glance</h2>
          </div>
          <div class="profilemid2">
              <div class="profilemid21">
                  <img src="images/mid-2.jpg" height="510px" width="100%" alt="">
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
                  <iframe class ="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3470.7716898968897!2d74.50246451510365!3d29.552142482064145!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39169479fadf422b%3A0xc5fa9af2b5fbb9d!2sShri+Siddhi+Vinayak+Shikshan+Sansthan!5e0!3m2!1sen!2sin!4v1541841188215" allowfullscreen="" loading="lazy"></iframe>
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
          <b class="toptext-3">Mr. Punit Kumar Kalyani</b><br>
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
          <img src="images/Pointer.png" alt=""> <br>
          <b class="toptext-3">Mr. Gauri Shankar Kalyani</b><br>
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
          </div>
      </div>
    </div>
  </div>
  `,
    management: `
  <div class="mid">
    <!-- Photo Gallery Section -->
    <section class="gallery">
      <h2>School Photo Gallery</h2>
      <div class="gallery-grid">
        <div class="gallery-item">
          <img src="images/director.jpeg" alt="Management Image 1" onclick="openLightbox(0, 'image')">
          <p class="image-title">Mr. Gauri Shankar Kanyani <br> (Director)</p>
        </div>
        <div class="gallery-item">
          <img src="images/principal.jpeg" alt="Management Image 2" onclick="openLightbox(1, 'image')">
          <p class="image-title">Mr. Punit Kalyani <br> (Principal)</p>
        </div>
        <div class="gallery-item">
          <img src="images/Treasurer.jpeg" alt="Management Image 3" onclick="openLightbox(2, 'image')">
          <p class="image-title">Mr. Seeta Ram Kalyani <br> (Treasurer)</p>
        </div>
        <div class="gallery-item">
          <img src="images/PNA.jfif" alt="Management Image 4" onclick="openLightbox(3, 'image')">
          <p class="image-title">Mrs. Durga Devi <br> (Member)</p>
        </div>
        <div class="gallery-item">
          <img src="images/PNA.jfif" alt="Management Image 5" onclick="openLightbox(4, 'image')">
          <p class="image-title">Mrs. Mr. Sunit Devi <br> (Member)</p>
        </div>
      </div>
    </section>
  </div>
  `,
    financials: `
  <div class="financials-section">
    <h2 class="financialmid">Financials</h2>
  
    <!-- FY 2023-24 -->
    <details class="fy-group" open>
        <summary class="fy-title">Financial Year 2023-24</summary>
        <div class="financials-grid">
            <div class="financial-card">
                <div class="financial-type">AUDIT REPORT</div>
                <div class="financial-year">2023-24</div>
                <a href="images/financials/AUDIT REPORT.pdf" target="_blank" class="pdf-link">
                    <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="PDF Icon">
                </a>
            </div>
        </div>
    </details>
  
    <!-- FY 2022-23 -->
    <details class="fy-group">
        <summary class="fy-title">Financial Year 2022-23</summary>
        <div class="financials-grid">
            <div class="financial-card">
                <div class="financial-type">AUDIT REPORT</div>
                <div class="financial-year">2022-23</div>
                <a href="images/financials/AUDIT REPORT 2022-23.pdf" target="_blank" class="pdf-link">
                    <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="PDF Icon">
                </a>
            </div>
            <div class="financial-card">
                <div class="financial-type">AUDIT REPORT</div>
                <div class="financial-year">2022-23</div>
                <a href="images/financials/AUDIT REPORT 2022-23.pdf" target="_blank" class="pdf-link">
                    <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="PDF Icon">
                </a>
            </div>
        </div>
    </details>
  
    <!-- FY 2020-21 -->
    <details class="fy-group">
        <summary class="fy-title">Financial Year 2020-21</summary>
        <div class="financials-grid">
            <div class="financial-card">
                <div class="financial-type">ITR</div>
                <div class="financial-year">2020-21</div>
                <a href="images/financials/ITR 2020-21.pdf" target="_blank" class="pdf-link">
                    <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="PDF Icon">
                </a>
            </div>
        </div>
    </details>
  
    <!-- FY 2019-20 -->
    <details class="fy-group">
        <summary class="fy-title">Financial Year 2019-20</summary>
        <div class="financials-grid">
            <div class="financial-card">
                <div class="financial-type">ITR</div>
                <div class="financial-year">2019-20</div>
                <a href="images/financials/ITR 2019-20.pdf" target="_blank" class="pdf-link">
                    <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="PDF Icon">
                </a>
            </div>
        </div>
    </details>
  
    <!-- FY 2018-19 -->
    <details class="fy-group">
        <summary class="fy-title">Financial Year 2018-19</summary>
        <div class="financials-grid">
            <div class="financial-card">
                <div class="financial-type">ITR</div>
                <div class="financial-year">2018-19</div>
                <a href="images/financials/ITR 2018-19.pdf" target="_blank" class="pdf-link">
                    <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="PDF Icon">
                </a>
            </div>
        </div>
    </details>
  </div>
  `};
  
  // Function to load any of these static templates by key
  function loadStaticContent(key) {
    const container = document.getElementById('content-container');
    if (contentData[key]) {
      container.innerHTML = contentData[key];
      initializeSection(key);
    } else {
      container.innerHTML = '<h2>Content not found</h2>';
    }
  }
}