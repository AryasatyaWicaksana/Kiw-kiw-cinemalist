document.addEventListener("DOMContentLoaded", function() {
  const profilePicture = document.getElementById('profile-picture');
  const profileButton = document.querySelector('.profile-btn');

  function updatePadding() {
      if (profilePicture.complete && profilePicture.naturalWidth !== 0) {
          profileButton.style.padding = '0';
      } else {
          profileButton.style.padding = '1rem';
      }
  }

  updatePadding();

  profilePicture.onload = updatePadding;
  profilePicture.onerror = updatePadding;
});

const genres = [
    {
      "id": 28,
      "name": "Action"
    },
    {
      "id": 12,
      "name": "Adventure"
    },
    {
      "id": 16,
      "name": "Animation"
    },
    {
      "id": 35,
      "name": "Comedy"
    },
    {
      "id": 80,
      "name": "Crime"
    },
    {
      "id": 99,
      "name": "Documentary"
    },
    {
      "id": 18,
      "name": "Drama"
    },
    {
      "id": 10751,
      "name": "Family"
    },
    {
      "id": 14,
      "name": "Fantasy"
    },
    {
      "id": 36,
      "name": "History"
    },
    {
      "id": 27,
      "name": "Horror"
    },
    {
      "id": 10402,
      "name": "Music"
    },
    {
      "id": 9648,
      "name": "Mystery"
    },
    {
      "id": 10749,
      "name": "Romance"
    },
    {
      "id": 878,
      "name": "Science Fiction"
    },
    {
      "id": 10770,
      "name": "TV Movie"
    },
    {
      "id": 53,
      "name": "Thriller"
    },
    {
      "id": 10752,
      "name": "War"
    },
    {
      "id": 37,
      "name": "Western"
    }
  ]

const main = document.getElementById('main');
const form =  document.getElementById('form');
const search = document.getElementById('search');
const tagsEl = document.getElementById('tags');

const prev = document.getElementById('prev')
const next = document.getElementById('next')
const current = document.getElementById('current')
const yearForm = document.getElementById('year-form');
const yearInput = document.getElementById('year-input');
const ratingForm = document.getElementById('rating-form');
const ratingSelect = document.getElementById('rating-select');

var currentPage = 1;
var nextPage = 2;
var prevPage = 3;
var lastUrl = '';
var totalPages = 100;

var selectedGenre = []
setGenre();
function setGenre() {
    tagsEl.innerHTML= '';
    genres.forEach(genre => {
        const t = document.createElement('div');
        t.classList.add('tag');
        t.id=genre.id;
        t.innerText = genre.name;
        t.addEventListener('click', () => {
            if(selectedGenre.length == 0){
                selectedGenre.push(genre.id);
            }else{
                if(selectedGenre.includes(genre.id)){
                    selectedGenre.forEach((id, idx) => {
                        if(id == genre.id){
                            selectedGenre.splice(idx, 1);
                        }
                    })
                }else{
                    selectedGenre.push(genre.id);
                }
            }
            getMovies(API_URL + '&with_genres='+encodeURI(selectedGenre.join(',')))
            highlightSelection()
        })
        tagsEl.append(t);
    })
}

function highlightSelection() {
    const tags = document.querySelectorAll('.tag');
    tags.forEach(tag => {
        tag.classList.remove('highlight')
    })
    clearBtn()
    if(selectedGenre.length !=0){   
        selectedGenre.forEach(id => {
            const hightlightedTag = document.getElementById(id);
            hightlightedTag.classList.add('highlight');
        })
    }
}

function clearBtn(){
    let clearBtn = document.getElementById('clear');
    if(clearBtn){
        clearBtn.classList.add('highlight')
    }else{
            
        let clear = document.createElement('div');
        clear.classList.add('tag','highlight');
        clear.id = 'clear';
        clear.innerText = 'Clear x';
        clear.addEventListener('click', () => {
            selectedGenre = [];
            setGenre();            
            getMovies(API_URL);
        })
        tagsEl.append(clear);
    }
}

getMovies(API_URL);

ratingForm.addEventListener('change', (e) => {
  e.preventDefault();

  const selectedRating = ratingSelect.value;
  if (selectedRating !== '') {
      // Hitung rentang rating
      const minRating = parseFloat(selectedRating);
      const maxRating = minRating + 0.999;

      // URL dengan filter rating
      const ratingURL = `${API_URL}&vote_average.gte=${minRating}&vote_average.lte=${maxRating}`;
      getMovies(ratingURL);
  }
});

yearForm.addEventListener('submit', (e) => {
  e.preventDefault();

  const year = yearInput.value;
  if (year) {
      const yearURL = `${API_URL}&primary_release_year=${year}`;
      getMovies(yearURL);
      yearInput.value = ''; // Reset input setelah submit
  }
});

function getMovies(url) {
  lastUrl = url;
    fetch(url).then(res => res.json()).then(data => {
        if(data.results.length !== 0){
            showMovies(data.results);
            currentPage = data.page;
            nextPage = currentPage + 1;
            prevPage = currentPage - 1;
            totalPages = data.total_pages;

            if (currentPage === 1) {
              prev.innerText = '';
            } else {
              prev.innerText = prevPage;
            }
            current.innerText = currentPage;
            next.innerText = nextPage;

            if(currentPage <= 1){
                prev.classList.add('disabled');
                next.classList.remove('disabled')
            }else if(currentPage>= totalPages){
                prev.classList.remove('disabled');
                next.classList.add('disabled')
            }else{
                prev.classList.remove('disabled');
                next.classList.remove('disabled')
            }

            tagsEl.scrollIntoView({behavior : 'smooth'})

        }else{
            main.innerHTML= '<h1 class="no-results">No Results Found</h1>'
        }
    })
}


function showMovies(data) {
  main.innerHTML = '';

  data.forEach(movie => {
      const {title, poster_path, vote_average, overview, id, release_date} = movie;
      const date = release_date;
      const movieEl = document.createElement('div');
      movieEl.classList.add('movie');

      movieEl.innerHTML = `
          <img src="${poster_path ? IMG_URL + poster_path : "http://via.placeholder.com/1080x1580"}" alt="${title}">
          <div class="movie-info">
              <h3>${title}</h3>
              <span class="${getColor(vote_average)}">${vote_average}</span>
              <p><span>${date}</span></p>
          </div>
          <div class="button-container">
              <button class="like-btn" id="like-${id}"><span class="heart-icon" id="heart-${id}">❤️</span></button>
              <button class="complete-btn" id="complete-${id}"><span class="check-icon" id="check-${id}">+</span></button>
              <button class="ptw-btn" id="ptw-${id}"><span class="bookmark-icon" id="bookmark-${id}">🔖</span></button>
          </div>
          <div class="overview">
              <h3>Overview</h3>
              ${overview}
              <br/> 
              <button class="know-more" id="${id}">Know More</button>
          </div>
      `;

      main.appendChild(movieEl);

      let isLiked = false;
      let isCompleted = false;
      let isPlanToWatch = false;

      document.getElementById(`like-${id}`).addEventListener('click', () => {
        if (!isLiked) {
            isLiked = true;
            const checkIcon = document.getElementById(`heart-${id}`);
            checkIcon.textContent = '✔️';
            saveMovieAction(movie, "like_movie"); 
        } else {
            alert('This movie is already on your list!');
        }
      });

      document.getElementById(`complete-${id}`).addEventListener('click', () => {
        if (!isCompleted) {
            isCompleted = true;
            const checkIcon = document.getElementById(`check-${id}`);
            checkIcon.textContent = '✔️';
            saveMovieAction(movie, "complete_movie"); 
        } else {
            alert('This movie is already on your list!');
        }
      });

      document.getElementById(`ptw-${id}`).addEventListener('click', () => {
        if (!isPlanToWatch) {
            isPlanToWatch = true;
            const checkIcon = document.getElementById(`bookmark-${id}`);
            checkIcon.textContent = '✔️';
            saveMovieAction(movie, "plan_to_watch_movie"); 
        } else {
            alert('This movie is already on your list!');
        }
      });

      document.getElementById(id).addEventListener('click', () => {
          openNav(movie);
      });
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

form.addEventListener('submit', (e) => {
    e.preventDefault();

    const searchTerm = search.value;
    selectedGenre=[];
    setGenre();
    if(searchTerm) {
        getMovies(searchURL+'&query='+searchTerm)
    }else{
        getMovies(API_URL);
    }

});

const overlayContent = document.getElementById('overlay-content');
function openNav(movie) {
  let id = movie.id;
  fetch(BASE_URL + '/movie/'+id+'/videos?'+API_KEY).then(res => res.json()).then(videoData => {
    if(videoData){
      document.getElementById("myNav").style.width = "100%";
      if(videoData.results.length > 0){
        var embed = [];
        var dots = [];
        videoData.results.forEach((video, idx) => {
          let {name, key, site} = video

          if(site == 'YouTube'){
              
            embed.push(`
              <iframe width="800" height="350" style="margin-bottom:3rem;" src="https://www.youtube.com/embed/${key}" title="${name}" class="embed hide" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          
          `)

            dots.push(`
              <span class="dot">${idx + 1}</span>
            `)
          }
        })
        
        var content = `
        <h1 class="no-results">${movie.original_title}</h1>
        <br/>
        
        ${embed.join('')}
        <br/>

        <div class="dots">${dots.join('')}</div>
        
        `
        overlayContent.innerHTML = content;
        activeSlide=0;
        showVideos();
      }else{
        overlayContent.innerHTML = '<h1 class="no-results">No Results Found</h1>'
      }
    }
  })
}

function closeNav() {
  document.getElementById("myNav").style.width = "0%";
}

var activeSlide = 0;
var totalVideos = 0;

function showVideos(){
  let embedClasses = document.querySelectorAll('.embed');
  let dots = document.querySelectorAll('.dot');

  totalVideos = embedClasses.length; 
  embedClasses.forEach((embedTag, idx) => {
    if(activeSlide == idx){
      embedTag.classList.add('show')
      embedTag.classList.remove('hide')

    }else{
      embedTag.classList.add('hide');
      embedTag.classList.remove('show')
    }
  })

  dots.forEach((dot, indx) => {
    if(activeSlide == indx){
      dot.classList.add('active');
    }else{
      dot.classList.remove('active')
    }
  })
}

const leftArrow = document.getElementById('left-arrow')
const rightArrow = document.getElementById('right-arrow')

leftArrow.addEventListener('click', () => {
  if(activeSlide > 0){
    activeSlide--;
  }else{
    activeSlide = totalVideos-1;
  }

  showVideos()
})

rightArrow.addEventListener('click', () => {
  if(activeSlide < (totalVideos-1)){
    activeSlide++;
  }else{
    activeSlide = 0;
  }
  showVideos()
})

prev.addEventListener('click', () => {
  if(prevPage > 0){
    pageCall(prevPage);
  }
})

next.addEventListener('click', () => {
  if(nextPage <= totalPages){
    pageCall(nextPage);
  }
})

function pageCall(page){
  let urlSplit = lastUrl.split('?');
  let queryParams = urlSplit[1].split('&');
  let key = queryParams[queryParams.length -1].split('=');
  if(key[0] != 'page'){
    let url = lastUrl + '&page='+page
    getMovies(url);
  }else{
    key[1] = page.toString();
    let a = key.join('=');
    queryParams[queryParams.length -1] = a;
    let b = queryParams.join('&');
    let url = urlSplit[0] +'?'+ b
    getMovies(url);
  }
}

function saveMovieAction(movie, actionType) {
  const Year = movie.release_date ? movie.release_date.split('-')[0] : '0'; 
  const payload = {
      action: actionType,
      id: movie.id,
      title: movie.title,
      year: Year,
      poster_path: movie.poster_path ? movie.poster_path : null,
      rating: movie.vote_average,
      genre: movie.genre_ids.map(id => genres.find(g => g.id === id)?.name).join(", "),
      overview: movie.overview
  };

  fetch('../Dashboard/dashboard.php', { 
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams(payload)
  })
  .then(response => {
      if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
  })
  .then(data => {
      if (data.success) {
          console.log(data.message);
      } else {
          console.error("Server error:", data.message);
      }
  })
  .catch(error => {
      console.error("Fetch error:", error);
  });
}