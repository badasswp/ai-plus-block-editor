
import { select } from '@wordpress/data';
import apiFetch from "@wordpress/api-fetch";

const TestAI = async () => {
  const Heading = await apiFetch(
    {
      path: '/ai-plus-block-editor/v1/heading',
      method: 'POST',
      data: {
        id: 1,
        content: "One of the things I have learnt early in life is the power of keeping a positive attitude. Most people go through life, worried about what they'd do to get the next meal and forge ahead. I love working with the best guys but I also understand that mindset is more important than money."
      },
    }
  )

  console.log(Heading);
}

export default TestAI;
