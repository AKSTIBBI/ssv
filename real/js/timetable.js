    function showTimetable(classValue) {
        const container = document.getElementById('timetable-container');
        
        if (!classValue) {
            container.innerHTML = '<p class="select-message">Please select a class to view the time table.</p>';
            return;
        }
        
        // In a real application, this would fetch the timetable data from the server
        // For now, showing a template timetable for demonstration
        
        container.innerHTML = `
            <h3>Time Table for ${getClassName(classValue)}</h3>
            <div class="table-responsive">
                <table class="timetable">
                    <thead>
                        <tr>
                            <th>Time / Day</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>8:00 - 8:45</td>
                            <td>Assembly</td>
                            <td>${getSubject(classValue, 1)}</td>
                            <td>${getSubject(classValue, 2)}</td>
                            <td>${getSubject(classValue, 3)}</td>
                            <td>Assembly</td>
                            <td>${getSubject(classValue, 4)}</td>
                        </tr>
                        <tr>
                            <td>8:45 - 9:30</td>
                            <td>${getSubject(classValue, 5)}</td>
                            <td>${getSubject(classValue, 6)}</td>
                            <td>${getSubject(classValue, 7)}</td>
                            <td>${getSubject(classValue, 8)}</td>
                            <td>${getSubject(classValue, 9)}</td>
                            <td>${getSubject(classValue, 10)}</td>
                        </tr>
                        <tr>
                            <td>9:30 - 10:15</td>
                            <td>${getSubject(classValue, 11)}</td>
                            <td>${getSubject(classValue, 12)}</td>
                            <td>${getSubject(classValue, 13)}</td>
                            <td>${getSubject(classValue, 14)}</td>
                            <td>${getSubject(classValue, 15)}</td>
                            <td>${getSubject(classValue, 16)}</td>
                        </tr>
                        <tr>
                            <td>10:15 - 10:45</td>
                            <td colspan="6" class="break">Break</td>
                        </tr>
                        <tr>
                            <td>10:45 - 11:30</td>
                            <td>${getSubject(classValue, 17)}</td>
                            <td>${getSubject(classValue, 18)}</td>
                            <td>${getSubject(classValue, 19)}</td>
                            <td>${getSubject(classValue, 20)}</td>
                            <td>${getSubject(classValue, 21)}</td>
                            <td>${getSubject(classValue, 22)}</td>
                        </tr>
                        <tr>
                            <td>11:30 - 12:15</td>
                            <td>${getSubject(classValue, 23)}</td>
                            <td>${getSubject(classValue, 24)}</td>
                            <td>${getSubject(classValue, 25)}</td>
                            <td>${getSubject(classValue, 26)}</td>
                            <td>${getSubject(classValue, 27)}</td>
                            <td>Library</td>
                        </tr>
                        <tr>
                            <td>12:15 - 1:00</td>
                            <td>${getSubject(classValue, 28)}</td>
                            <td>${getSubject(classValue, 29)}</td>
                            <td>${getSubject(classValue, 30)}</td>
                            <td>${getSubject(classValue, 31)}</td>
                            <td>${getSubject(classValue, 32)}</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>1:00 - 1:45</td>
                            <td>Sports</td>
                            <td>Computer Lab</td>
                            <td>Art & Craft</td>
                            <td>Music/Dance</td>
                            <td>Counseling</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>1:45 - 2:30</td>
                            <td>Extra Activities</td>
                            <td>Extra Activities</td>
                            <td>Extra Activities</td>
                            <td>Extra Activities</td>
                            <td>Extra Activities</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
    }
    
    function getClassName(classValue) {
        const classNames = {
            'nursery': 'Nursery',
            'kg': 'Kindergarten',
            '1': 'Class 1',
            '2': 'Class 2',
            '3': 'Class 3',
            '4': 'Class 4',
            '5': 'Class 5',
            '6': 'Class 6',
            '7': 'Class 7',
            '8': 'Class 8',
            '9': 'Class 9',
            '10': 'Class 10',
            '11-science': 'Class 11 - Science',
            '11-commerce': 'Class 11 - Commerce',
            '11-arts': 'Class 11 - Arts',
            '12-science': 'Class 12 - Science',
            '12-commerce': 'Class 12 - Commerce',
            '12-arts': 'Class 12 - Arts'
        };
        
        return classNames[classValue] || classValue;
    }
    
    function getSubject(classValue, seed) {
        // Simple function to generate consistent subjects based on class and position
        const primarySubjects = ['English', 'Hindi', 'Math', 'EVS', 'Moral Science', 'GK', 'Drawing'];
        const middleSubjects = ['English', 'Hindi', 'Math', 'Science', 'Social Science', 'Sanskrit', 'Computer'];
        const highSubjects = ['English', 'Hindi', 'Math', 'Science', 'Social Science', 'Physical Education', 'Computer'];
        const scienceSubjects = ['Physics', 'Chemistry', 'Biology', 'Math', 'English', 'Computer Science', 'Physical Education'];
        const commerceSubjects = ['Accountancy', 'Business Studies', 'Economics', 'English', 'Math', 'Computer Applications', 'Physical Education'];
        const artsSubjects = ['History', 'Political Science', 'Geography', 'English', 'Hindi', 'Fine Arts', 'Physical Education'];
        
        let subjects;
        
        if (classValue === 'nursery' || classValue === 'kg') {
            subjects = ['Play', 'Drawing', 'Rhymes', 'Story Time', 'Numbers', 'Alphabets', 'Outdoor Activities'];
        } else if (['1', '2', '3', '4', '5'].includes(classValue)) {
            subjects = primarySubjects;
        } else if (['6', '7', '8'].includes(classValue)) {
            subjects = middleSubjects;
        } else if (['9', '10'].includes(classValue)) {
            subjects = highSubjects;
        } else if (classValue === '11-science' || classValue === '12-science') {
            subjects = scienceSubjects;
        } else if (classValue === '11-commerce' || classValue === '12-commerce') {
            subjects = commerceSubjects;
        } else if (classValue === '11-arts' || classValue === '12-arts') {
            subjects = artsSubjects;
        } else {
            subjects = primarySubjects;
        }
        
        return subjects[seed % subjects.length];
    }