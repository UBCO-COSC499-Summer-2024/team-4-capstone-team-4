@vite(['resources/css/staff.css'])
<section class="dash-staff-preview">
</section>
<template id="staff-preview">
    <div class="staff-preview-item">
            <svg id="staff-preview-img" viewBox="0 0 480 480" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" >
                <defs>
                    <clipPath id="blob">
                        <path fill="#474bff" d="M382,335.5Q350,431,253.5,407.5Q157,384,106,312Q55,240,113,180Q171,120,249,104.5Q327,89,370.5,164.5Q414,240,382,335.5Z" />
                    </clipPath>
                </defs>
                <image x="0" y="0" width="100%" height="100%" clip-path="url(#blob)" xlink:href="https://images.unsplash.com/photo-1520333789090-1afc82db536a?crop=entropy&amp;cs=tinysrgb&amp;fit=max&amp;fm=jpg&amp;ixid=M3wzNjMxMDZ8MHwxfHJhbmRvbXx8fHx8fHx8fDE3MTY1ODY5ODd8&amp;ixlib=rb-4.0.3&amp;q=80&amp;w=1080" preserveAspectRatio="xMidYMid slice"></image>
            </svg>
        <h5></h5>
    </div>
</template>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
        const profList = document.querySelector('.dash-staff-preview');
        const num = 48;
        const staffPrev = document.querySelector('#staff-preview').content;
        console.log(staffPrev)

        fetch(`https://randomuser.me/api/?results=${num}`)
            .then(response => response.json())
            .then(data => {
                data.results.forEach(user => {
                    const profElement = staffPrev.cloneNode(true);
                    const img = profElement.querySelector('image');
                    img.setAttribute('xlink:href', user.picture.thumbnail);
                    profElement.querySelector('h5').textContent = `${user.name.first} ${user.name.last}`;
                    profList.appendChild(profElement);
                });
            })
            .catch(error => console.error('Error fetching random users:', error));
    });
</script>