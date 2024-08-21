document.addEventListener('DOMContentLoaded', () => {
    fetchPosts();
});


let fetchedPosts = []


function toggleMenu() {
    const nav = document.querySelector('nav');
    const hamburger = document.querySelector('.hamburger');
    nav.classList.toggle('active');
    hamburger.classList.toggle('active');
}


function fetchPosts() {
    let posts = [];
    fetch('home.php?getPosts=true')
        .then(response => response.json())
        .then(items => {
            posts.push(items);
            for(const post of posts[0]){
                fetchedPosts.push(post)
            }
            displayPosts(items);
        })
        .catch(error => console.error('Error fetching items:', error));
}

function displayPosts(posts) {
    const postsGrid = document.querySelector('.main-content');
    postsGrid.innerHTML = '';
    
   

    for (let i = 0; i < posts.length; i++) {
        const post = posts[i];
        let postElement = document.createElement('div');
        postElement.classList.add('post');
        postElement.innerHTML = `
            <div class="post-header">
                <span class="username">Animal shelter</span>
                <a href="edit_post.php?post_id=${post.id}">
                    ${isAdmin ? `<a href="edit_post.php?post_id=${post.id}"><ion-icon name="create-outline"></ion-icon></a>` : ''}
                </a>
            </div>
            <div class="post-images">
                <img src="${post.image}" alt="${post.description}">
            </div>
            <div class="post-actions">
                <form method="post" action="save_likes.php">
                    <input type="hidden" name="post_id" value="${post.id}">
                    <input type="hidden" name="like_action" value="${post.user_liked==1 ? 'unlike' : 'like'}">
                    <button type="submit" class="like-button">
                        <ion-icon name="${post.user_liked==1 ? 'heart' : 'heart-outline'}" class="like-icon" data-id="${post.id}"></ion-icon>
                    </button>
                </form>
                <a href="comments.php?post_id=${post.id}">
                    <ion-icon name="chatbubble-outline"></ion-icon>
                </a>
            </div>
            <div class="post-likes" data-id="${post.id}">${post.likes} likes</div>
            <div class="post-description">${post.description}</div>
            <div class="post-comments"><a href="comments.php?post_id=${post.id}">${post.comments} comments</a></div>
            <div class="post-time">${getTimeAgo(post.created_at)}</div>
        `;
        postsGrid.appendChild(postElement);

        const likeButton = postElement.querySelector('.like-icon');
        likeButton.addEventListener('click', (event) => {
            event.preventDefault(); 
            likePost(post.id, likeButton);
        });
    };
}


function likePost(postId, element) {
    const postLikesElement = document.querySelector(`.post-likes[data-id="${postId}"]`);
    let newLikes = 0;

    if (element.getAttribute('name') === "heart-outline") {
        element.setAttribute('name', 'heart');
        element.style.color = "red";
        const currentLikes = parseInt(postLikesElement.textContent);
        newLikes = currentLikes + 1;
        postLikesElement.textContent = `${newLikes} likes`;
        element.closest('form').querySelector('input[name="like_action"]').value = "like";
    } else {
        element.setAttribute('name', 'heart-outline');
        element.style.color = "black";
        const currentLikes = parseInt(postLikesElement.textContent);
        newLikes = currentLikes - 1;
        postLikesElement.textContent = `${newLikes} likes`;
        element.closest('form').querySelector('input[name="like_action"]').value = "unlike";
    }

    element.closest('form').submit();
}





function getTimeAgo(created_at) {
    const currentDate = new Date();
    const postDate = new Date(created_at);
    const diffSeconds = Math.floor((currentDate - postDate) / 1000);

    if (diffSeconds < 60) {
        return `${diffSeconds} seconds ago`;
    } else if (diffSeconds < 3600) {
        const diffMinutes = Math.floor(diffSeconds / 60);
        return `${diffMinutes} minutes ago`;
    } else if (diffSeconds < 86400) {
        const diffHours = Math.floor(diffSeconds / 3600);
        return `${diffHours} hours ago`;
    } else {
        const diffDays = Math.floor(diffSeconds / 86400);
        return `${diffDays} days ago`;
    }
}







