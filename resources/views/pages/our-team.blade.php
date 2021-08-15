@extends('layouts.app')
@section('content')
<div class="page-main p-0">
    <div class="front-banner our-team">
        <div class="content-wraper">
            <div class="container">
                <div class="content">
                    <h1 class="text-white">Our Team</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="page-wraper">
       <div class="our-team-wraper">
            <div class="container">
                <div class="team-lists">
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <div class="team-wraper">
                                <div class="team-media">
                                    <img src="{{ Helper::assets('images/our-team/abdul.jpg') }}" alt="" class="w-100">
                                    <div class="team-desc">
                                        <p>The President of MEA is Abdul Rahman. Abdul was born and raised in Queens, NY with his parents originally from Pakistan. Abdul had shown great leadership skills in College becoming the First Muslim President of the Economics Honor Society at Queens College, being a founder of the Trading & Investing Competition and launching his first startup FixMe which had its office in Queens College Technology Incubator. Abdul has given much back to his community and is very involved with his Alma Mater Queens College. He sits on the Young Alumni Board and both organized and led the QC Business & Tech Expo for 3 years where both Global and Local companies would come to Queens College Campus to take part in the yearly expo event. Currently, Abdul is pursuing his passion for both Finance & Tech where he is working full time as a VP for a Hedge Fund, managing teams globally and performing risk and operations across multi-assets and multi-million valued portfolios. Abdul also runs a software and digital marketing firm, DeployMe. The company has a global team that builds websites, mobile apps, SEO, Social Media Marketing, IT, cybersecurity, Online Marketing and other technology services for their clients. They also consult small businesses on how to grow their online footprint, expand their brand and take their business to the next level using technology.</p>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title">Abdul Rahman</h4>
                                    <p class="designation">President</p>
                                    <div class="team-social-icons">
                                        <ul class="m-0 p-0">
                                            <li><a href="mailto:Abdul.Rahman@MEA-Community.com"><i class="fa fa-envelope"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="team-wraper">
                                <div class="team-media">
                                    <img src="{{ Helper::assets('images/our-team/shiekh.jpg') }}" alt="" class="w-100">
                                    <div class="team-desc">
                                        <p>The Vice President of MEA is Sheikh Galib Rahman. Sheikh is from Bangladesh and came to NY during his College years in order to pursue his passion for IT & Tech. Sheikh has had a robust career in IT & Technology, from working for JPM, Capgemini, Homeland Security, and the Federal Reserve Sheikh had hired and led multiple teams around the world. He recently left his job at Accenture where he was the Global Program Manager for QA in order to run his IT Training School Full Time in Queens Called Transfotech. Sheikh is highly active in the community and most recently returned from a trip to Bangladesh where he helped organize a visit by 5 US Senators to discuss ideas and possible synergies.</p>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title">Sheikh Galib Rahman</h4>
                                    <p class="designation">Vice President</p>
                                    <div class="team-social-icons">
                                        <ul class="m-0 p-0">
                                            <li><a href="mailto:Galib.Rahman@MEA-Community.com"><i class="fa fa-envelope"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="team-wraper">
                                <div class="team-media">
                                    <img src="{{ Helper::assets('images/our-team/zaman.jpeg') }}" alt="" class="w-100">
                                    <div class="team-desc">
                                        <p>Mohammed Zaman is currently the Treasurer for MEA and an accounting manager at SSTL Inc, an ecommerce firm specializing in furniture sales and CRM software licencing. He's also founder of Swiftpark, an app that helps users find and share parking spots. Previously he used to day trade futures with Topsteptrader. He graduated from Baruch college in 2012 with BBA in Finance, Magna Cum Laude. He also trades options, and reads books as a hobby.</p>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title">Mohammad Zaman</h4>
                                    <p class="designation">Treasurer</p>
                                    <div class="team-social-icons">
                                        <ul class="m-0 p-0">
                                            <li><a href="mailto:zaman@mea-community.com"><i class="fa fa-envelope"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="team-wraper">
                                <div class="team-media">
                                    <img src="{{ Helper::assets('images/our-team/rizwana.jpeg') }}" alt="" class="w-100">
                                    <div class="team-desc">
                                        <p>Rizwana Rahim Bashir, is General Secretary of MEA. Born and brought up in Bangladesh, Rizwana moved to USA recently with family for better future of her children. Rizwana had a promising career in the fast moving and dynamic banking industry of Bangladesh, having almost 12 years of work experience with HSBC, a leading multi-national British Bank operating in Bangladesh. She worked her way from an Associate to Vice President, Business Development, Global Trade and Receivables Finance. She has worked closely with Senior Management in various capacities including working a number of years with the present CEO of HSBC Bangladesh. Rizwana has gathered valuable knowledge and practical experience in commercial banking customer handling, leadership and corporate culture. She has been an advocate of women’s rights and empowerment issues. Born to entrepreneurial parents, entrepreneurship is close to her heart.</p>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title">Rizwana Rahim</h4>
                                    <p class="designation">General Secretary</p>
                                    <div class="team-social-icons">
                                        <ul class="m-0 p-0">
                                            <li><a href="mailto:rizwana@mea-community.com"><i class="fa fa-envelope"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="team-wraper">
                                <div class="team-media">
                                    <img src="{{ Helper::assets('images/our-team/Zahra.jpg') }}" alt="" class="w-100">
                                    <div class="team-desc">
                                        <p>Zahra Khan is a New York City General Education and English language teacher, writing tutor, career coach, and owner of ResumesbyZahra.com. She also boasts experience as a Human Resources Supervisor, Writing professor at Queensborough Community College, as well as a background in the Fashion, Art, and Beauty industries.</p>
                                        <p>Zahra has helped hundreds of clients, from CEOs, lawyers, artists, and doctors through her professional consulting and career service, which include resume and cover letter writing, job hunting strategies, interview etiquette, and career transitioning. She credits her ability to help others find success in their career journey to her wide range of experiences, all which have helped her understand the “dos” and “don’ts” of job hunting and industry expectations of their job candidates.</p>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title">Zahra Khan</h4>
                                    <p class="designation">Director - MEA</p>
                                    <div class="team-social-icons">
                                        <ul class="m-0 p-0">
                                            <li><a href="#"><i class="fa fa-envelope"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="team-wraper">
                                <div class="team-media">
                                    <img src="{{ Helper::assets('images/our-team/Abdul-Rehman-butt.jpg') }}" alt="" class="w-100">
                                    <div class="team-desc">
                                        <p>Abdul Rehman Butt was born in Pakistan and immigrated to USA in 2015. He graduated from the University of the Punjab where he got his Bachelors in Business Sciences in the year 2000.</p>
                                        <p>Abdul is the founder of DINAMIKA School of Entrepreneurs which offers FREE hundred days courses to anyone who wants to succeed in business and money-making. As a Professor of Entrepreneurship, author, and international public speaker he has awakened an entrepreneurial spirit in hundreds of his students and helped dozens of corporate teams achieve higher goals. After earning his degree in Business Sciences, he set a quest for his life to answer one big question ‘Why over 90% of Businesses Fail?’ He lived in different parts of the world, from South Asia to The Middle East to Europe to Latin America, read every book he could, met great teachers, mentors, and successful family business owners, and learned their secrets of nurturing younger generations from childhood. But the real magic occurred when he discovered his on Fail-Proof Formula which is a new approach of teaching business through practical methodology, he calls it ‘The DINAMIKA Class.</p>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title">Abdul Rehman Butt</h4>
                                    <p class="designation">Director - MEA</p>
                                    <div class="team-social-icons">
                                        <ul class="m-0 p-0">
                                            <li><a href="#"><i class="fa fa-envelope"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="team-wraper">
                                <div class="team-media">
                                    <img src="{{ Helper::assets('images/our-team/Fahad.jpg') }}" alt="" class="w-100">
                                    <div class="team-desc">
                                        <p>Fahad Rajput is a seasoned executive involved with the innovation of new ideas in Real Estate development. He has been featured in Yahoo Finance, ABC, and Fox. Fahad manages Blacklist Capital, which is a premier private equity firm boasting above-average investor returns leveraged over multiple assets across downstate New York. He is a TEDx speaker in entrepreneurship and a Citation Honoree by the Town of Hempstead for outstanding achievement. As a private sector partner to Department of Homeland Security's HSIN, a member of the United States Air Force Auxiliary and a Chaplain in New York State's Chaplain Task Force, Fahad advocates giving back to his community.</p>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title">Fahad Rajput</h4>
                                    <p class="designation">Director - MEA</p>
                                    <div class="team-social-icons">
                                        <ul class="m-0 p-0">
                                            <li><a href="#"><i class="fa fa-envelope"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="team-wraper">
                                <div class="team-media">
                                    <img src="{{ Helper::assets('images/our-team/Ismail-Kolya-headshot.jpg') }}" alt="" class="w-100">
                                    <div class="team-desc">
                                        <p>Born in India, Ismail (Ish) Kolya, migrated to the US in 1991 at the age of 13. Since then, Ish grew up in the Bronx of NY and pursued his education in Health Services Administration. After college, Ish held an array of administrative, management and sales positions.Today, based out of lower Westchester and the Bronx, Ish is a Licensed Real Estate Associate Broker with eXp Realty. Also, Ish leads a vibrant team of realtors at the Team of Experts and is a Licensed Real Estate Instructor.</p>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title">Ismail (Ish) Kolya</h4>
                                    <p class="designation">Director - MEA</p>
                                    <div class="team-social-icons">
                                        <ul class="m-0 p-0">
                                            <li><a href="#"><i class="fa fa-envelope"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="team-wraper">
                                <div class="team-media">
                                    <img src="{{ Helper::assets('images/our-team/Jonaed-scaled.jpg') }}" alt="" class="w-100">
                                    <div class="team-desc">
                                        <p>Jonaed Iqbal is a career consultant based in New York City. He has set out with a goal to assist people without college degrees find new job opportunities. He is the founder of NoDegree.com. The website is home to an innovative local job search platform, which aims to connect people with suitable career paths, even without formal Degrees.</p>
                                        <p>Based in the New York City area, Jonaed has an extensive professional background across various industries. He has served as an actuary at MetLife and worked as a financial analyst and consultant for New York City public schools. Believing in the potential of his idea, he decided to launch NoDegree.com and pursue speaking engagements on a full-time basis. He now focuses on running NoDegree.com.</p>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title">Jonaed Iqbal</h4>
                                    <p class="designation">Director - MEA</p>
                                    <div class="team-social-icons">
                                        <ul class="m-0 p-0">
                                            <li><a href="#"><i class="fa fa-envelope"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="team-wraper">
                                <div class="team-media">
                                    <img src="{{ Helper::assets('images/our-team/Profile-Pic-M-Saleem-2020.jpg') }}" alt="" class="w-100">
                                    <div class="team-desc">
                                        <p>Mohammad A. Saleem is a founding Partner and currently the Managing Partner of Davis Ndanusa Ikhlas & Saleem LLP. His practice areas focus on Immigration Law, Real Estate Transactions & Litigation, Estate Planning and Administration/Probate matters. Mr. Saleem received his Juris Doctorate in 2009 from Pennsylvania State University’s Dickinson School of Law where he was a member of the law school’s Philip C. Jessup International Law Moot Court Competition team and his Bachelor of Arts in 2006 from Hunter College. Mr. Saleem is admitted to practice law in New York State as well as Federal District Courts of Southern District of New York (SDNY) and Eastern District of New York (EDNY), the U.S. Court of Appeals for the Second Circuit, and the U.S. Supreme Court. He has been recognized for his legal service to the NYC community in the area of foreclosure defense in 2012 by the New York State Bar Association and the Queens County Bar Association.</p>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title">Mohammad A. Saleem</h4>
                                    <p class="designation">Director - MEA</p>
                                    <div class="team-social-icons">
                                        <ul class="m-0 p-0">
                                            <li><a href="#"><i class="fa fa-envelope"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="team-wraper">
                                <div class="team-media">
                                    <img src="{{ Helper::assets('images/our-team/Radwan-PP.jpg') }}" alt="" class="w-100">
                                    <div class="team-desc">
                                        <p>Radwan Chowdhury serves as the founder and CEO of AA Global Solutions, a leading technology consulting firm and founder and CEO of Foody, a national quick-service chain restaurant, he is the founder and honorary chair of the board of UDiON Foundation a global non-profit organization based in the United States that works with underprivileged children and their families to stop child labor, extend educational opportunities, and provide access to healthcare in Bangladesh.</p>
                                        <p>Radwan is a motivational speaker, author, activist, and a social entrepreneur. Radwan served as Chair of Jacksonville Mayor Asian American Advisory Board (MAAAB), and Council Member to Jacksonville Public Service Grant (PSG) Commission, in addition, he served as a board of directors to many profit and non-profit organizations. Throughout his career, Radwan has received widespread recognition, awards, and honors for his work, including the Life Time Service Award “Call to Service” by President Barak Obama in 2011, Diversity and Inclusion Award by El-Beth Development Center, he was listed as Most Influential Asian American in NE FL by Indo-US Chamber of Commerce, he was also the nominee of 2015 OneJax Humanitarian Award. Moreover, Radwan consistently received academic excellence awards and commendations for his diverse business background, for his entrepreneurial acumen; sharp business skills; a vision that transformed</p>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title">Radwan Chowdhury</h4>
                                    <p class="designation">Director- MEA</p>
                                    <div class="team-social-icons">
                                        <ul class="m-0 p-0">
                                            <li><a href="#"><i class="fa fa-envelope"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="team-wraper">
                                <div class="team-media">
                                    <img src="{{ Helper::assets('images/our-team/salma.jpg') }}" alt="" class="w-100">
                                    <div class="team-desc">
                                        <p>Salma is the founder and CEO of Paid Meals. PaidMeals is mobile App dedicated to fighting hunger. A platform for us to all pitch in and buy meals for people who cannot afford meals, in a dignified manner</p>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title">Salma Khan</h4>
                                    <p class="designation">Director- MEA</p>
                                    <div class="team-social-icons">
                                        <ul class="m-0 p-0">
                                            <li><a href="#"><i class="fa fa-envelope"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="team-wraper">
                                <div class="team-media">
                                    <img src="{{ Helper::assets('images/our-team/Shafi.jpg') }}" alt="" class="w-100">
                                    <div class="team-desc">
                                        <p>A lawyer experienced winning difficult yet rewarding cases. Seasoned with courtroom and trial experience, especially in front of the the Immigration Courts of New York, New Jersey and Connecticut. Additionally, experienced handling and winning immigration cases before United States Citizenship and Immigration Services. Also successfully handled real estate, personal injury, civil litigation and divorce cases.</p>
                                    </div>
                                </div>                        
                                <div class="team-content">
                                    <h4 class="title">Shafi Chowdhury </h4>
                                    <p class="designation">Director - MEA</p>
                                    <div class="team-social-icons">
                                        <ul class="m-0 p-0">
                                            <li><a href="#"><i class="fa fa-envelope"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="team-wraper">
                                <div class="team-media">
                                    <img src="{{ Helper::assets('images/our-team/Walla-Kelbpics.jpg') }}" alt="" class="w-100">
                                    <div class="team-desc">
                                        <p>Walla Elsheikh is the Co-Founder and CEO of Birthright AFRICA which is committed to providing a free educational trip to Africa for all youth and young adults of African descent in the US. She has over 16 years of management and leadership experience in both the private and public sectors and has spent the last 10 years supporting various college and career readiness initiatives in both the K-12 and Postsecondary education spaces that prepare students with skills required in the 21st century global economy. Walla is the former Interim Executive Director of Schools That Can, NYC and has served as a program director, internship coordinator, and strategic consultant to small and large educational non-profits in NYC and Washington, D.C. She received her MBA in strategic management and social entrepreneurship from Indiana University after working as a Financial Associate at Goldman Sachs & Co. Walla participated in both the Education Pioneers and Management Leadership for Tomorrow (MLT) Fellowships and was selected as one of the Most Influential People of African descent (MIPAD) in 2018. She was born in Sudan and raised there as well as Sweden and Uganda before immigrating to New York City.</p>
                                    </div>
                                </div>
                                <div class="team-content">
                                    <h4 class="title">Walla Elsheikh</h4>
                                    <p class="designation">Director - MEA</p>
                                    <div class="team-social-icons">
                                        <ul class="m-0 p-0">
                                            <li><a href="#"><i class="fa fa-envelope"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       </div>
    </div>
</div>
@endsection