/**
 * No photo found set default
 * @param {*} event
 */
function noPhoto(event) {
  event.src = `${window.location.origin}/static/no-photo-pink.png`;
  event.alt = 'default';
}

// declare targetDivs array
let targetDivs;
// create an object to store impression counts for each element
const impressionCounts = {};

// function to check if element is in view
function isInViewport(element) {
  const rect = element.getBoundingClientRect();
  return (
      rect.top >= 0 &&
      rect.bottom <= window.innerHeight
  );
}

// function to handle scroll event with timeout
let timeoutId;

function handleScrollWithTimeout() {
  // clear any existing timeout
  clearTimeout(timeoutId);
  // set new timeout for N ms
  timeoutId = setTimeout(() => {
    targetDivs.forEach((div) => {
      if (isInViewport(div) && !impressionCounts[div.id]) {
        //collect impressions for sending to API
        const impressionBatch = [calculateImpressions(div)];
        let prevSibling = div;
        do {
          prevSibling = getPreviousProfileEl(prevSibling);
          if (prevSibling) {
            const impression = calculateImpressions(prevSibling);
            impressionBatch.push(impression);
          }
        } while(prevSibling);

        sendImpressions(impressionBatch);
      }
    });
  }, 100);
}

function getPreviousProfileEl(div) {
  const sibling = div.previousElementSibling;
  if(sibling.dataset?.pos && !impressionCounts[sibling.id]) {
    return sibling;
  }
  return null;
}

function calculateImpressions(div) {
  const imgSrc = new URL(div.children[0].getElementsByTagName('img')[0].currentSrc).pathname;
  const profileData = div.children[0].getElementsByTagName('a')[0].dataset;
  // increment impression count for this element if it's in view and hasn't been counted before
  const impression = {
    isAd: profileData.context === "ad",
    image: imgSrc,
    position: profileData.pos,
    username: profileData.username,
    bData: profileData.b
  };
  impressionCounts[div.id] = impression;
  return impression;
}

function sendImpressions(impressions) {
  const origin = window.origin;
  const baseUrl = new URL(`${origin}/api/v1/analytics/impressions`);
  baseUrl.searchParams.append('ref', window.location);
  fetch(baseUrl, {
    method:'POST',
    headers: {
      "Content-Type": "application/json",
      'Accept': 'application/json',
    },
    body: JSON.stringify(impressions)
  })
  .then(response => {})
  .catch(error => console.error(error));
}

// add event listener to window object to set targetDivs after page is loaded
window.addEventListener('DOMContentLoaded', () => {
  // set last updated date to a random date in the last 6 hours
  const lastUpdatedDate = new Date(
      (new Date()).getTime() - Math.floor(Math.random() * 6 * 60 * 60 * 1000)
  );
  const lastUpdatedDateStr = new Intl.DateTimeFormat('en-US',
      {day: 'numeric', month: 'long', year: 'numeric'}).format(lastUpdatedDate);
  document.getElementById('refresh-date').innerHTML =
      `Last updated: <time datetime="${lastUpdatedDate.toISOString()}">${lastUpdatedDateStr}</time>`;

  const jsonLdData = {
    "@context": "https://schema.org",
    "@type": "NewsArticle",
    "author": {
      "@type": "Organization",
      "name": "OnlyFinder.com"
    },
    "datePublished": lastUpdatedDate.toISOString()
  };

  const jsonLdScript = document.createElement('script');
  jsonLdScript.type = "application/ld+json";
  jsonLdScript.text = JSON.stringify(jsonLdData, null, 2);
  document.head.appendChild(jsonLdScript);

  // select the div elements to track impressions
  targetDivs = document.querySelectorAll('.target-div');
  //always collect impressions for first few rows on load
  window.addEventListener('scroll', handleScrollWithTimeout);
});