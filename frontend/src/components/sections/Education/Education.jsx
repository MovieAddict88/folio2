import React, { useState, useEffect } from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import './Education.css';

const Education = () => {
  const [educationData, setEducationData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch('/api/education.php')
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        if (data.error) {
          throw new Error(data.error);
        }
        setEducationData(data);
        setLoading(false);
      })
      .catch(error => {
        setError(error.message);
        setLoading(false);
      });
  }, []);

  if (loading) return <div className="education-section"><h2>Education</h2><p>Loading...</p></div>;
  if (error) return <div className="education-section"><h2>Education</h2><p>Error: {error}</p></div>;

  return (
    <div className="education-section">
      <h2>Education</h2>
      <Swiper
        modules={[Navigation, Pagination]}
        spaceBetween={50}
        slidesPerView={1}
        navigation
        pagination={{ clickable: true }}
      >
        {educationData.map((edu, index) => (
          <SwiperSlide key={index}>
            <div className="education-item">
              <h3>{edu.degree}</h3>
              <p><strong>{edu.institution}</strong></p>
              <p>{edu.start_year} - {edu.end_year}</p>
              <p>{edu.description}</p>
            </div>
          </SwiperSlide>
        ))}
      </Swiper>
    </div>
  );
};

export default Education;