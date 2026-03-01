    function showSyllabus(classLevel) {
        const container = document.getElementById('syllabus-container');
        
        if (!classLevel) {
            container.innerHTML = '<p class="select-message">Please select a class level to view the syllabus.</p>';
            return;
        }
        
        // In a real application, this would fetch the syllabus data from the server
        // For now, showing template content for demonstration
        
        let syllabusHTML = '';
        
        if (classLevel === 'primary') {
            syllabusHTML = getPrimarySyllabus();
        } else if (classLevel === 'middle') {
            syllabusHTML = getMiddleSyllabus();
        } else if (classLevel === 'secondary') {
            syllabusHTML = getSecondarySyllabus();
        } else if (classLevel === 'senior-science') {
            syllabusHTML = getSeniorScienceSyllabus();
        } else if (classLevel === 'senior-commerce') {
            syllabusHTML = getSeniorCommerceSyllabus();
        } else if (classLevel === 'senior-arts') {
            syllabusHTML = getSeniorArtsSyllabus();
        }
        
        container.innerHTML = syllabusHTML;
    }
    
    function getPrimarySyllabus() {
        return `
            <h3>Primary Section Syllabus (Classes 1-5)</h3>
            
            <div class="Syllabusaccordion">
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">English</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Reading:</strong> Phonics, sight words, reading comprehension, fiction and non-fiction texts</p>
                        <p><strong>Writing:</strong> Handwriting, sentence formation, paragraph writing, creative writing</p>
                        <p><strong>Grammar:</strong> Parts of speech, tenses, punctuation, sentence structure</p>
                        <p><strong>Speaking and Listening:</strong> Oral presentations, discussions, listening comprehension</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Mathematics</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Numbers:</strong> Number recognition, counting, place value, comparing numbers</p>
                        <p><strong>Operations:</strong> Addition, subtraction, multiplication, division</p>
                        <p><strong>Measurement:</strong> Length, weight, capacity, time, money</p>
                        <p><strong>Geometry:</strong> Shapes, spatial awareness, patterns</p>
                        <p><strong>Data Handling:</strong> Simple graphs, charts, data collection</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Environmental Studies (EVS)</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Family and Society:</strong> Family structure, community helpers, neighborhood</p>
                        <p><strong>Plants and Animals:</strong> Basic characteristics, habitats, life cycles</p>
                        <p><strong>Food and Health:</strong> Nutrition, hygiene, body parts, senses</p>
                        <p><strong>Earth and Environment:</strong> Weather, seasons, natural resources, conservation</p>
                        <p><strong>Transportation and Communication:</strong> Modes of transport, communication tools</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Hindi</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Reading:</strong> Alphabet recognition, word reading, simple texts</p>
                        <p><strong>Writing:</strong> Letter formation, word writing, simple sentences</p>
                        <p><strong>Grammar:</strong> Basic grammar structures, gender, number</p>
                        <p><strong>Speaking and Listening:</strong> Oral expression, stories, poems</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Art and Craft</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Drawing and Coloring:</strong> Basic shapes, coloring techniques, patterns</p>
                        <p><strong>Craft:</strong> Paper folding, clay modeling, collage making</p>
                        <p><strong>Art Appreciation:</strong> Colors, textures, basic art forms</p>
                    </div>
                </div>
            </div>
        `;
    }
    
    function getMiddleSyllabus() {
        return `
            <h3>Middle Section Syllabus (Classes 6-8)</h3>
            
            <div class="Syllabusaccordion">
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">English</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Literature:</strong> Prose, poetry, drama, literary devices</p>
                        <p><strong>Language:</strong> Advanced grammar, vocabulary development, idioms and phrases</p>
                        <p><strong>Writing:</strong> Essays, letters, notices, reports, creative writing</p>
                        <p><strong>Speaking and Listening:</strong> Debates, discussions, presentations</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Mathematics</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Number System:</strong> Integers, fractions, decimals, rational numbers</p>
                        <p><strong>Algebra:</strong> Variables, expressions, equations, identities</p>
                        <p><strong>Geometry:</strong> Lines, angles, polygons, circles, constructions</p>
                        <p><strong>Mensuration:</strong> Area, perimeter, volume, surface area</p>
                        <p><strong>Data Handling:</strong> Statistics, probability, graphs</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Science</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Physics:</strong> Motion, force, energy, light, sound, electricity, magnetism</p>
                        <p><strong>Chemistry:</strong> Matter, elements, compounds, mixtures, chemical reactions</p>
                        <p><strong>Biology:</strong> Plants, animals, human body, cells, microorganisms, environment</p>
                        <p><strong>Practical Work:</strong> Experiments, observations, scientific method</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Social Science</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>History:</strong> Ancient, medieval, modern history of India and the world</p>
                        <p><strong>Geography:</strong> Earth, landforms, climate, natural resources, maps</p>
                        <p><strong>Civics:</strong> Government, democracy, rights and duties, constitution</p>
                        <p><strong>Economics:</strong> Basic economic concepts, livelihood, market, public facilities</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Computer Science</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Fundamentals:</strong> Computer parts, operating systems, file management</p>
                        <p><strong>Applications:</strong> Word processing, spreadsheets, presentations</p>
                        <p><strong>Internet:</strong> Web browsing, email, internet safety</p>
                        <p><strong>Programming:</strong> Introduction to algorithms, basic coding concepts</p>
                    </div>
                </div>
            </div>
        `;
    }
    
    function getSecondarySyllabus() {
        return `
            <h3>Secondary Section Syllabus (Classes 9-10)</h3>
            
            <div class="Syllabusaccordion">
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">English</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Literature:</strong> Prose, poetry, drama from prescribed textbooks</p>
                        <p><strong>Language:</strong> Advanced grammar, vocabulary, editing, omission</p>
                        <p><strong>Writing:</strong> Essays, letters (formal and informal), articles, reports, speeches</p>
                        <p><strong>Assessment:</strong> Reading comprehension, literature questions, creative writing</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Mathematics</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Number Systems:</strong> Real numbers, Euclid's division lemma</p>
                        <p><strong>Algebra:</strong> Polynomials, linear equations, quadratic equations</p>
                        <p><strong>Coordinate Geometry:</strong> Coordinate plane, distance formula, section formula</p>
                        <p><strong>Geometry:</strong> Triangles, circles, constructions, similar triangles</p>
                        <p><strong>Trigonometry:</strong> Trigonometric ratios, identities, heights and distances</p>
                        <p><strong>Mensuration:</strong> Areas, surface areas and volumes</p>
                        <p><strong>Statistics and Probability:</strong> Mean, median, mode, probability concepts</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Science</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Physics:</strong> Motion, force and laws of motion, gravitation, work and energy, sound, light</p>
                        <p><strong>Chemistry:</strong> Matter, atoms and molecules, structure of atom, chemical reactions, acids, bases and salts, metals and non-metals, carbon compounds</p>
                        <p><strong>Biology:</strong> Cell, tissues, diversity in living organisms, life processes, control and coordination, reproduction, heredity and evolution, natural resources</p>
                        <p><strong>Practical Work:</strong> Laboratory experiments, record keeping, project work</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Social Science</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>History:</strong> French Revolution, Russian Revolution, Nazism, nationalism in Europe and India, industrialization</p>
                        <p><strong>Geography:</strong> Resources, agriculture, minerals, industries, transport, communication, population</p>
                        <p><strong>Political Science:</strong> Democracy, constitution, electoral politics, working of institutions, democratic rights</p>
                        <p><strong>Economics:</strong> Development, sectors of Indian economy, money and credit, globalization</p>
                        <p><strong>Project Work:</strong> Research-based projects, case studies, field trips</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Information Technology</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Basics of Information Technology:</strong> Computer systems, software applications</p>
                        <p><strong>Web Applications:</strong> Web pages, HTML, web browsers, websites</p>
                        <p><strong>Word Processing:</strong> Advanced formatting, mail merge, macros</p>
                        <p><strong>Spreadsheet:</strong> Functions, formulas, data analysis, charts</p>
                        <p><strong>Database Management:</strong> Creating databases, queries, forms, reports</p>
                        <p><strong>Practical Work:</strong> Hands-on exercises, projects, presentations</p>
                    </div>
                </div>
            </div>
        `;
    }
    
    function getSeniorScienceSyllabus() {
        return `
            <h3>Senior Secondary Syllabus - Science Stream (Classes 11-12)</h3>
            
            <div class="Syllabusaccordion">
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Physics</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Class 11:</strong> Physical world and measurement, Kinematics, Laws of motion, Work, energy and power, Motion of system of particles and rigid body, Gravitation, Properties of bulk matter, Thermodynamics, Behaviour of perfect gas and kinetic theory, Oscillations and waves</p>
                        <p><strong>Class 12:</strong> Electrostatics, Current electricity, Magnetic effects of current and magnetism, Electromagnetic induction and alternating currents, Electromagnetic waves, Optics, Dual nature of matter and radiation, Atoms and nuclei, Electronic devices, Communication systems</p>
                        <p><strong>Practical Work:</strong> Laboratory experiments, project work, investigatory projects</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Chemistry</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Class 11:</strong> Some basic concepts of chemistry, Structure of atom, Classification of elements and periodicity in properties, Chemical bonding and molecular structure, States of matter, Thermodynamics, Equilibrium, Redox reactions, Hydrogen, s-Block elements, p-Block elements, Organic chemistry, Hydrocarbons, Environmental chemistry</p>
                        <p><strong>Class 12:</strong> Solid state, Solutions, Electrochemistry, Chemical kinetics, Surface chemistry, General principles and processes of isolation of elements, p-Block elements, d and f Block elements, Coordination compounds, Haloalkanes and haloarenes, Alcohols, phenols and ethers, Aldehydes, ketones and carboxylic acids, Amines, Biomolecules, Polymers, Chemistry in everyday life</p>
                        <p><strong>Practical Work:</strong> Salt analysis, organic compounds preparation, titrations</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Biology</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Class 11:</strong> Diversity in living world, Structural organization in animals and plants, Cell structure and function, Plant physiology, Human physiology</p>
                        <p><strong>Class 12:</strong> Reproduction, Genetics and evolution, Biology and human welfare, Biotechnology and its applications, Ecology and environment</p>
                        <p><strong>Practical Work:</strong> Microscopy studies, experiments, spotting, project work</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Mathematics</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Class 11:</strong> Sets, Relations and functions, Trigonometric functions, Principle of mathematical induction, Complex numbers and quadratic equations, Linear inequalities, Permutations and combinations, Binomial theorem, Sequences and series, Straight lines, Conic sections, Introduction to three-dimensional geometry, Limits and derivatives, Mathematical reasoning, Statistics, Probability</p>
                        <p><strong>Class 12:</strong> Relations and functions, Inverse trigonometric functions, Matrices, Determinants, Continuity and differentiability, Applications of derivatives, Integrals, Applications of integrals, Differential equations, Vector algebra, Three-dimensional geometry, Linear programming, Probability</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">English</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Reading Comprehension:</strong> Unseen passages, note-making, summarizing</p>
                        <p><strong>Writing Skills:</strong> Essays, articles, reports, letters, notices</p>
                        <p><strong>Literature:</strong> Prose, poetry, plays from prescribed textbooks</p>
                        <p><strong>Grammar:</strong> Advanced grammar structures, editing, omission</p>
                    </div>
                </div>
            </div>
        `;
    }
    
    function getSeniorCommerceSyllabus() {
        return `
            <h3>Senior Secondary Syllabus - Commerce Stream (Classes 11-12)</h3>
            
            <div class="Syllabusaccordion">
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Accountancy</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Class 11:</strong> Introduction to accounting, Theory base of accounting, Recording of transactions, Preparation of financial statements, Depreciation, provisions and reserves, Accounting for bills of exchange, Rectification of errors, Financial statements of sole proprietorship</p>
                        <p><strong>Class 12:</strong> Accounting for partnership firms, Accounting for companies, Analysis of financial statements, Cash flow statement, Computerized accounting</p>
                        <p><strong>Practical Work:</strong> Preparation of financial statements, ratio analysis, project work</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Business Studies</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Class 11:</strong> Nature and purpose of business, Forms of business organizations, Private, public and global enterprises, Business services, Emerging modes of business, Social responsibility of business and business ethics, Formation of a company, Sources of business finance, Small business, Internal trade</p>
                        <p><strong>Class 12:</strong> Nature and significance of management, Principles of management, Business environment, Planning, Organizing, Staffing, Directing, Controlling, Financial management, Financial markets, Marketing management, Consumer protection, Entrepreneurship development</p>
                        <p><strong>Project Work:</strong> Case studies, market surveys, business reports</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Economics</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Class 11:</strong> Introduction to microeconomics, Consumer's equilibrium, Demand, Production and costs, Supply, Price determination, Market structures, Measures of central tendency, Correlation, Index numbers</p>
                        <p><strong>Class 12:</strong> Introduction to macroeconomics, National income accounting, Money and banking, Determination of income and employment, Government budget and the economy, Balance of payments, International economics, Development economics, Indian economic development</p>
                        <p><strong>Project Work:</strong> Economic surveys, data analysis, research projects</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Mathematics</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Class 11:</strong> Sets, Relations and functions, Trigonometric functions, Principle of mathematical induction, Complex numbers and quadratic equations, Linear inequalities, Permutations and combinations, Binomial theorem, Sequences and series, Straight lines, Conic sections, Introduction to three-dimensional geometry, Limits and derivatives, Mathematical reasoning, Statistics, Probability</p>
                        <p><strong>Class 12:</strong> Relations and functions, Inverse trigonometric functions, Matrices, Determinants, Continuity and differentiability, Applications of derivatives, Integrals, Applications of integrals, Differential equations, Vector algebra, Three-dimensional geometry, Linear programming, Probability</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">English</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Reading Comprehension:</strong> Unseen passages, note-making, summarizing</p>
                        <p><strong>Writing Skills:</strong> Essays, articles, reports, letters, notices</p>
                        <p><strong>Literature:</strong> Prose, poetry, plays from prescribed textbooks</p>
                        <p><strong>Grammar:</strong> Advanced grammar structures, editing, omission</p>
                    </div>
                </div>
            </div>
        `;
    }
    
    function getSeniorArtsSyllabus() {
        return `
            <h3>Senior Secondary Syllabus - Arts Stream (Classes 11-12)</h3>
            
            <div class="Syllabusaccordion">
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">History</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Class 11:</strong> Introduction to world history, Early societies, Empires, Changing traditions, Paths to modernization</p>
                        <p><strong>Class 12:</strong> Themes in Indian history (Ancient, Medieval, Modern), Archaeology and ancient history, Agrarian relations, New architecture, Religious histories, Medieval society, Colonial cities, Mahatma Gandhi and nationalist movement, Partition of India, Framing of the Constitution</p>
                        <p><strong>Project Work:</strong> Historical research, case studies, documentary analysis</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Political Science</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Class 11:</strong> Political theory, Constitution at work, Democracy and diversity, Local governments, Political ideologies, Introduction to comparative politics</p>
                        <p><strong>Class 12:</strong> Contemporary world politics, Cold War era, New centers of power, South Asia and the contemporary world, United Nations, Globalization, Challenges to democracy, Politics in India since independence, Election system, Federalism, Planning and development, India's foreign policy</p>
                        <p><strong>Project Work:</strong> Political surveys, policy analysis, research projects</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Geography</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Class 11:</strong> Physical geography (Geomorphology, Climatology, Oceanography, Biogeography), Human geography (Population, settlements, resources)</p>
                        <p><strong>Class 12:</strong> Fundamentals of human geography, People and resources, Human activities, Transport and communication, Human settlements, India - physical environment, People and economy, Geographical perspective on selected issues and problems</p>
                        <p><strong>Practical Work:</strong> Map work, field surveys, GIS introduction, project work</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">Economics</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Class 11:</strong> Introduction to microeconomics, Consumer's equilibrium, Demand, Production and costs, Supply, Price determination, Market structures, Measures of central tendency, Correlation, Index numbers</p>
                        <p><strong>Class 12:</strong> Introduction to macroeconomics, National income accounting, Money and banking, Determination of income and employment, Government budget and the economy, Balance of payments, International economics, Development economics, Indian economic development</p>
                        <p><strong>Project Work:</strong> Economic surveys, data analysis, research projects</p>
                    </div>
                </div>
                
                <div class="Syllabusaccordion-item">
                    <h4 class="Syllabusaccordion-header">English</h4>
                    <div class="Syllabusaccordion-content">
                        <p><strong>Reading Comprehension:</strong> Unseen passages, note-making, summarizing</p>
                        <p><strong>Writing Skills:</strong> Essays, articles, reports, letters, notices</p>
                        <p><strong>Literature:</strong> Prose, poetry, plays from prescribed textbooks</p>
                        <p><strong>Grammar:</strong> Advanced grammar structures, editing, omission</p>
                    </div>
                </div>
            </div>
        `;
    }

    
    // Add Syllabusaccordion functionality
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('Syllabusaccordion-header')) {
            const content = e.target.nextElementSibling;
            const allContents = document.querySelectorAll('.Syllabusaccordion-content');
            
            // Hide all other Syllabusaccordion contents
            allContents.forEach(item => {
                if (item !== content) {
                    item.style.display = 'none';
                }
            });
            
            // Toggle current content
            if (content.style.display === 'block') {
                content.style.display = 'none';
            } else {
                content.style.display = 'block';
            }
        }
    });
