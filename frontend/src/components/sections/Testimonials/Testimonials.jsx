import React, { useState, useEffect } from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import './Testimonials.css';

const Testimonials = () => {
  const [testimonials, setTestimonials] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch('/api/testimonials.php')
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          throw new Error(data.error);
        }
        setTestimonials(data);
        setLoading(false);
      })
      .catch(error => {
        setError(error.message);
        setLoading(false);
      });
  }, []);

  const getEmbedUrl = (url) => {
    if (!url) return null;
    try {
      const urlObj = new URL(url);
      if (urlObj.hostname.includes('youtube.com')) {
        const videoId = urlObj.searchParams.get('v');
        return `https://www.youtube.com/embed/${videoId}`;
      }
      return url;
    } catch (e) {
      return null;
    }
  };

  if (loading) return <div className="testimonials-section"><h2>Testimonials</h2><p>Loading...</p></div>;
  if (error) return <div className="testimonials-section"><h2>Testimonials</h2><p>Error: {error}</p></div>;

  return (
    <div className="testimonials-section">
      <h2>Testimonials</h2>
      <Swiper
        modules={[Navigation, Pagination]}
        spaceBetween={50}
        slidesPerView={1}
        navigation
        pagination={{ clickable: true }}
      >
        {testimonials.map((testimonial, index) => (
          <SwiperSlide key={index}>
            <div className="testimonial-item">
              {testimonial.video_url ? (
                <div className="testimonial-video-container">
                  <iframe
                    src={getEmbedUrl(testimonial.video_url)}
                    title={`Testimonial from ${testimonial.author_name}`}
                    frameBorder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowFullScreen
                  ></iframe>
                </div>
              ) : (
                <p>"{testimonial.testimonial_text}"</p>
              )}
              <div className="author">
                {testimonial.author_image_url && <img src={testimonial.author_image_url} alt={testimonial.author_name} />}
                <div>
                  <strong>{testimonial.author_name}</strong>
                  <span>{testimonial.author_position}</span>
                </div>
              </div>
            </div>
          </SwiperSlide>
        ))}
      </Swiper>
    </div>
  );
};

export default Testimonials;