const newList = document.getElementById('newList');

getMovies(API_URL);

function getMovies(url) {
    lastUrl = url;
    fetch(url).then(res => res.json()).then(data => {
        if(data.results.length !== 0){
            showMovies(data.results);
        }else{
            newList.innerHTML= '<h1 class="no-results">No Results Found</h1>'
        }
    })
}

function showMovies(data) {
    newList.innerHTML = '';

    data.forEach(movie => {
        const {title, poster_path, vote_average} = movie;
        const movieEl = document.createElement('div');
        movieEl.classList.add('movie');

        movieEl.innerHTML = `
            <img src="${poster_path ? IMG_URL + poster_path : "http://via.placeholder.com/1080x1580"}" alt="${title}">
        `;

        newList.appendChild(movieEl);
    });
}

function getColor(vote) {
    if(vote>= 8){
        return 'green'
    }else if(vote >= 5){
        return "orange"
    }else{
        return 'red'
    }
}