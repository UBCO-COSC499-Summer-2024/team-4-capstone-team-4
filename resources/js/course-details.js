const { l } = ("vite/dist/node/types.d-aGj9QkWt");

document.addEventListener('DOMContentLoaded', function () {

    const assignButton = document.getElementById('assignButton');
    if (assignButton) {
        assignButton.addEventListener('click', function () {
            $('#assignModal').modal('show');
        });
    }
    
    fetch('/api/users')
        .then(response=>response.json())
        .then(users=>{
        const userList=document.getElementById('instructor-list');

        instructors.forEach(user=>{
            const userItem=this.document.createElement('li');
            userItem.className = 'instructor-item';
            userItem.dataset.id = user.id;
            userItem.textContent = `${user.firstname} ${user.lastname}`;
            userItem.appendChild(userItem);
        });
        document.querySelectorAll('.instructor-item').forEach(function(item){
            item.addEventListener('click',function(){
                let instructorId=this.dataset.id;
                fetch(`/api/users/{$user_id}`)
                .then(response=>response.json())
                .then(data=>{
                    let tbody=document.querySelectorAll('.course_sections tbody');
                    tbody.innerHTML='';

                    data.forEach(item=>{
                        let row=document.createElement('tr');
                        row.innerHTML=`
                        <td class="px-6 py-4 whitespace-nowrap">${item.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${item.duration}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${item.enrolled}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${item.dropped}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${item.capacity}</td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error=>{
                    console.error('There was a problem in fetching the operation',error);
                });
            });
        });
    })
    .catch(error=>{
       console.error('There was a problem in fetching the operation',error);     
    });
});

function addSorting(){
    document.querySelectorAll('.coursedetails-table th').forEach(header=>{
        header.addEventListener('click',function(){
            const tableElements=header.parentElement.parentElement.parentElement;
            const headerIndex=Array.prototype.indexOf.call(header.parentElement.children,header);
            const currentAscending=header.classList.contains('th-sort-asc');

            sortTableByColumn(tableElements,headerIndex,currentAscending);
        });
});
}

function sortTableByColumn(table,column, asc=true){
    const directoryModifier=acs ? 1:-1;
    const tbody=table.querySelectorAll('tbody');
    const rows=Array.from(tbody.querySelectorAll('tr'));

    const sortedRows=rows.sort((a,b)=>{
        const colAText=a.querySelectorAll(`td:nth-child(${column+1})`).textContent.trim();
        const colBText=b.querySelectorAll(`td:nth-child(${column+1})`).textContent.trim();
        
        return colAText>colBText?(1*directoryModifier):(-1*directoryModifier);
    });

    while(tbody.firstChild){
        tbody.removeChild(tbody.firstChild);
    }
    tbody.appendChild(...sortedRows);

    table.querySelectorAll('th').forEach(th=>th.classList.remove('th-sort-asc','th-sort-desc'));
    header.classList.toggle('th-sort-asc',asc);
    header.classList.toggle('th-sort-desc',!asc);
}